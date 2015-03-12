<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class LGLConfig {
	static function getAvailableLeagues() {
		static $leagues;
		if (is_null($leagues)) {
			$db = JFactory::getDbo();
			$query = "SELECT league_id FROM #__lgl_leagues WHERE ustatus = 'active'";
			$db->setQuery($query);
			$leagues = $db->loadColumn();
		}
		return $leagues;
	}
}