<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined('_JEXEC') or die;
JLoader::registerPrefix('LGL', JPATH_SITE . '/components/com_lgl/lib');

$app = JFactory::getApplication();
$task = $app->input->get('task');

if ($task) {
	try {
		require_once(JPATH_ADMINISTRATOR . '/components/com_lgl/controllers/' . $task . '.php');
		$className = ucfirst(strtolower($task)) . 'Controller';
		$controller = new $className();
		$controller->execute();
	} catch (Exception $x) {
	}
	$app->close();
}

require_once(JPATH_ADMINISTRATOR . '/components/com_lgl/controller.php');
$controller = new LGLController();
$controller->execute();
