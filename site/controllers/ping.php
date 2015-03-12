<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class PingController extends  JControllerBase {

	private $now;
	private $luts;

	private function init() {
		$this->now = time();
		$this->luts = $this->getInput()->getInt('lu');
		if (!$this->luts) {
			throw new InvalidArgumentException("lu not set");
		}
	}

	function execute() {
		$this->init();
		$selector = new LGLDataSelectorMatch();
		$selector->published();
		$selector->lastupdate($this->luts + 1);
		$selector->begintime($this->now - 5 * 86400, $this->now + 86400);
		$selector->leagues(LGLConfig::getAvailableLeagues());
		$result = $selector->select();
		$items = array();
		if ($result->count()) {
			$items = array_values($result->getItems());
		}
		foreach ($items as $item) {
			/** @var $item LGLDataItemMatch */
			$this->luts = max($this->luts, $item->lastupdatetime);
		}
		echo json_encode(array(
			'status' => 'OK',
			'items' => $items,
			'luts' => $this->luts
		));
		$this->getApplication()->close();
	}
}