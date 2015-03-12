<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



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