de<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class LGLDataItemStandingsRow extends JData {
	function __construct($arr = array()) {
		parent::__construct($arr);
		$this->setProperty('details', new JData(json_decode($this->details, true)));
		$this->setProperty('matches', new JData(json_decode($this->matches, true)));
		$this->setProperty('goals', new JData(json_decode($this->goals, true)));
	}

}