<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );

JLoader::registerPrefix('LGL', JPATH_SITE . '/components/com_lgl/lib');

$app = JFactory::getApplication();
$input = $app->input;
$task = $input->get('task');
$controllerFile = JPATH_COMPONENT . "/controllers/$task.php";
$controllerClass = ucfirst($task) . 'Controller';
if (is_readable($controllerFile)) {
	require_once($controllerFile);
	/** @var $controller JControllerBase */
	$controller = new $controllerClass();
	$controller->execute();
}
$app->close(0);


