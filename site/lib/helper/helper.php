<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


class LGLHelper {
	static function loadJSTranslations() {
		$strings = array(
			'finished',
			'canceled',
			'suspended',
			'postponed'
		);
		foreach ($strings as $str) {
			JText::script($str);
		}
	}
}

