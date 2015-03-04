<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

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

