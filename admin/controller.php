<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

class LGLController extends JControllerBase {

	function execute() {
		require_once(JPATH_COMPONENT . '/views/lgl/view.php');
		/** @var $view JViewHtml */
		$view = new LGLView();
		echo $view->render();
	}
}