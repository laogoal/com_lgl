<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT . '/controllers/cmd.php');

class EnableController extends CmdController {

	private function sendCommandToProvider($command, array $params = array()) {
		return LGLClient::getInstance()->sendCommand($command, $params);
	}

	protected function cmdPing() {
		try {
			$result = $this->sendCommandToProvider('Info', array('checkUrl' => true));
		} catch (LGLClientException $x) {
			throw new CmdControllerException('Can not connect to provider. ' . $x->getMessage());
		}
		if (!isset($result['status']) || !isset($result['expires'])) {
			throw new CmdControllerException("Provider returned an invalid response");
		}
		if ($result['expires'] < time()) {
			throw new CmdControllerException("This application's subscription is expired");
		}
		$this->sendCommandToProvider('Pause', array('pause' => true));
		$oldUrl = null;
		if (isset($result['listener_url'])) {
			$oldUrl = trim($result['listener_url']);
		}
		$currentUri = JUri::getInstance();
		if (!empty($oldUrl)) {
			$oldUri = new JUri($oldUrl);
			$oldHost = $oldUri->getHost();
			$oldHost = strtolower(preg_replace('/^www\./si', '', $oldHost));
			$currentHost = $currentUri->getHost();
			$currentHost = strtolower(preg_replace('/^www\./si', '', $currentHost));
			if (0 != strcmp($currentHost, $oldHost)) {
				$msg = "Can not change host from $oldHost to $currentHost. ";
				$msg .= 'Automatic updates can be enabled only for ' . $oldHost;
				throw new CmdControllerException($msg);
			}
		}
		$newUrl = 'http://' . $currentUri->getHost();
		if ($port = $currentUri->getPort()) {
			$newUrl .= ":$port";
		}
		$newUrl .= $_SERVER['PHP_SELF'] . '?option=com_lgl&task=cmd';
		$newUrl = str_replace('administrator/', '', $newUrl);
		try {
			$this->sendCommandToProvider('Url', array('url' => $newUrl));
		} catch (LGLClientException $x) {
			throw new CmdControllerException(
				'The provider could not setup ' . $newUrl . ' as an updates receiver. ' . $x->getMessage()
			);
		}
		return true;
	}

	protected function cmdSync() {
		$count = 0;
		try {
			LGLDataHelper::pullLeaguesInfo();
			//TODO: нужен реальный luts
			$luts = mktime(0, 0, 0, 1, 1, 2015);
			$this->sendCommandToProvider('Luts', array('luts' => $luts));
			$data = $this->sendCommandToProvider('Updates');
			if (isset($data['matches']) && is_array($data['matches']) && sizeof($data['matches'])) {
				$count += LGLDataHelper::processMatches($data['matches']);
			}
			if (isset($data['standings']) && is_array($data['standings']) && sizeof($data['standings'])) {
				$count += LGLDataHelper::processStandings($data['standings']);
			}
		} catch (Exception $x) {
			throw new CmdControllerException(
				'Can not synchronize data with provider. ' . $x->getMessage()
			);
		}
		return $count;
	}

	protected function cmdStatus() {
		try {
			$this->sendCommandToProvider('Pause', array('pause' => false));
			$this->setParam('updates_enabled', true);
		} catch (Exception $x) {
			throw new CmdControllerException($x->getMessage());
		}
		return true;
	}
}
