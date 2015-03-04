<?php
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