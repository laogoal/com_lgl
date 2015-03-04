de<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


class LGLDataItemStandingsRow extends JData {
	function __construct($arr = array()) {
		parent::__construct($arr);
		$this->setProperty('details', new JData(json_decode($this->details, true)));
		$this->setProperty('matches', new JData(json_decode($this->matches, true)));
		$this->setProperty('goals', new JData(json_decode($this->goals, true)));
	}

}