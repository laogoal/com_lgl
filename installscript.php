<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class com_lglInstallerScript {
	public function __construct(JAdapterInstance $adapter) {
	}

	public function preflight($route, JAdapterInstance $adapter) {
	}

	public function postflight($route, JAdapterInstance $adapter) {
		if ('install' == $route) {

			try {
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->delete()->from('#__menu');
				$query->where('menutype = ' . $db->quote('main'));
				$query->where('client_id = 1');
				$query->where('link = ' . $db->quote('index.php?option=com_lgl'));
				$query->where('type = ' . $db->quote('component'));
				$query->where('parent_id = 1');
				$query->where('home = 0');
				$db->setQuery($query);
				$db->execute();
			} catch (Exception $x) {

			}

			echo "LaoGoaL Core component has been successfully installed. Now you need to configure it";
		}
	}

	public function install(JAdapterInstance $adapter) {
	}

	public function update(JAdapterInstance $adapter) {
	}

	public function uninstall(JAdapterInstance $adapter) {
	}
}
