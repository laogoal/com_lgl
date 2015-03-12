<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_COMPONENT . '/models/lgl.php');

class LGLView extends JViewHtml {

	function __construct() {
		$this->paths = $this->loadPaths();
		$this->paths->insert(dirname(__FILE__) . '/tmpl', 0);

		$doc = JFactory::getDocument();
		JHTML::_('behavior.framework',true);
		$doc->addScript(JUri::base() . 'components/com_lgl/media/js/installer.js');
		$doc->addStyleSheet(JUri::base() . 'components/com_lgl/media/css/admin.css');

		$this->model = new LGLModel();
		$this->createToolbar();
	}

	protected function createToolbar() {
		JToolbarHelper::title(JText::_('LaoGoaL Core Component'), 'inbox.png');
		JToolbarHelper::preferences('com_lgl');
	}
}