<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );


abstract class CmdController extends JControllerBase {
	function execute() {
		$cmdName = 'cmd' . ucfirst($this->input->get('cmd', 'null'));
		if (!is_callable(array($this, $cmdName))) {
			echo json_encode(array('status' => 'error', 'error' => 'unrecognized command'));
			return;
		}
		try {
			$params = $this->input->get('params', array(), 'array');
			$data = call_user_func_array(array($this, $cmdName), $params);
			echo json_encode(array('status' => 'ok', 'data' => $data));
		} catch (Exception $x) {
			echo json_encode(array('status' => 'error', 'error' => $x->getMessage()));
		}
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return bool
	 */
	protected function setParam($key, $value) {
		/** @var $params JRegistry */
		$params = JComponentHelper::getParams('com_lgl');
		$params->set($key, $value);
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__extensions');
		$query->set('params=' . $db->quote($params->toString()));
		$query->where("`type` = 'component' AND `element` = 'com_lgl'");
		$db->setQuery($query);
		$db->execute();
		return true;
	}

}
class CmdControllerException extends Exception {

}
