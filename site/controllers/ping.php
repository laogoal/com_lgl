<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


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