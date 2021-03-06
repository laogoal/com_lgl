<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );


class LGLController extends JControllerBase {

	function execute() {
		require_once(JPATH_COMPONENT . '/views/lgl/view.php');
		/** @var $view JViewHtml */
		$view = new LGLView();
		echo $view->render();
	}
}