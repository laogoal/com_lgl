<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


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