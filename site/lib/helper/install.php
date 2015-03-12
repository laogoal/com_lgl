<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


class LGLHelperInstall {

	static function jsPostInstall() {
		JHTML::_('behavior.framework',true);
		$base = JUri::base();
		if (false === strpos($base, '/administrator')) {
			$base .= 'administrator/';
		}
		echo '<script type="text/javascript" src="' . $base . 'components/com_lgl/media/js/installer.js"></script>';
		echo '<link rel="stylesheet" type="text/css" href="' . $base . 'components/com_lgl/media/css/installer.css" />';
		echo '
<script type="text/javascript">
window.addEvent(
	"domready",
	function(){
		window.LGLInstaller.run(
			"' . $base . '",
			$("system-message-container"),
"LaoGoaL Core component installed SUCCESSFULLY.<br > It is necessary to check for last updates from data provider"
		);
	}
);
</script>';

	}
}


class LGLInstallationException extends Exception {

}