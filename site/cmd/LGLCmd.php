<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


abstract class LGLCmd {

	/**
	 * @param $cmdName
	 * @param array $arguments
	 * @return LGLCmd
	 */
	static function getInstance($cmdName, array $arguments = array()) {
		$path = dirname(__FILE__);
		$className = str_replace(' ', '', ucwords(str_replace('_', ' ', $cmdName))) . 'Cmd';
		require_once (JPath::find(array($path), $className . '.php'));
		return new $className($arguments);
	}

	function __construct(array $arguments = array()) {
		$this->setArguments($arguments);
	}

	protected $arguments;

	/**
	 * @param mixed $arguments
	 */
	function setArguments($arguments) {
		$this->arguments = $arguments;
	}

	abstract protected function _execute();

	function execute() {
		return $this->_execute();
	}
}