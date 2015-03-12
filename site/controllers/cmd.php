<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


jimport('joomla.controller.base');
require_once(JPATH_COMPONENT . '/cmd/LGLCmd.php');


class CmdController extends JControllerBase {


	static private $allowedCommands = array(
		'ping',
		'acknowledge'
	);

	function getCmd() {
		return $this->input->getString('cmd');
	}

	function getArguments() {
		return json_decode($this->input->get('args', null, 'raw'), true);
	}

	private function _authorize() {
		$cmd = $this->getCmd();
		if (empty($cmd) || !in_array($cmd, self::$allowedCommands)) {
			throw new CmdControllerException('Unrecognized command ' . $cmd);
		}

		$crc = $this->input->getString('crc');
		if (strlen($crc) != 32) {
			throw new CmdControllerException('Invalid data checksum ' . $crc);
		}

		$params = JComponentHelper::getParams('com_lgl');
		$apikey = $params->get('apikey');
		if (strlen($apikey) != 32) {
			throw new CmdControllerException('Invlaid APIKEY ' . $apikey);
		}

		if (md5($cmd . $this->input->get('args', null, 'raw') . md5($apikey)) != $crc) {
			throw new CmdControllerException('Unconfirmed data. ' . "[$apikey, $crc]");
		}
		return true;
	}

	function execute() {
		JLog::addLogger(
			array(
				'text_file' => 'com_lgl.log'
			),
			JLog::ALL,
			'lgl'
		);
		try {
			JLog::add('START New provider command from ' . $this->input->server->get('REMOTE_ADDR'), JLog::INFO, 'lgl');
			$this->_authorize();
			$cmd = $this->getCmd();
			$arguments = $this->getArguments();
			JLog::add('Authorized. Command: ' . $cmd, JLog::INFO, 'lgl');
			$command = LGLCmd::getInstance($cmd, (array) $arguments);
			JLog::add('Data: ' . substr(json_encode($arguments), 0, 64), JLog::INFO, 'lgl');
			$response = array(
				'status' => 'ok',
				'data' => $command->execute()
			);
			JLog::add('Processed successfully', JLog::INFO, 'lgl');
		} catch (CmdControllerException $x) {
			JLog::add($x->getMessage(), JLog::WARNING, 'lgl');
			$response = array(
				'status' => 'err',
				'error' => array(
					'code' => $x->getCode(),
					'message' => $x->getMessage()
				)
			);
		}
		JLog::add("END\n", JLog::INFO, 'lgl');
		echo json_encode($response);
		return true;
	}
}

class CmdControllerException extends Exception {

}