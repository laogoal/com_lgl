<?php

defined('_JEXEC') or die;
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


