<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class LGLDataSelectorMatch extends  LGLDataSelector {
	function __construct() {
		$mapping = array(
			'id' => 'match_id',
			'published' => 'published',
			'league' => 'league_id',
			'hosts' => 'hosts',
			'guests' => 'guests',
			'status' => 'status',
			'current' => 'current',
			'details' => 'details',
			'events' => 'events',
			'begintime' => 'sts',
			'lastupdatetime' => 'luts',
			'crc' => 'crc',
			'score' => 'score'
		);
		parent::__construct('LGLDataSetMatch', '#__lgl_matches', $mapping, JFactory::getDbo());
	}

	/**
	 * @param string $id
	 */
	function id($id) {
		$this->dropField('id');
		$this->appendEq('id', $id);
	}

	/**
	 * @param array $leagues
	 */
	function leagues(array $leagues) {
		$this->dropField('league');
		$this->appendIn('league', $leagues);
	}

	/**
	 * @param int $from
	 * @param int $to
	 */
	function begintime($from = null, $to = null) {
		$this->dropField('begintime');
		$this->appendBetween('begintime', $from, $to);
	}

	/**
	 * @param bool $bool
	 */
	function published($bool = true) {
		$this->dropField('published');
		$this->appendEq('published', $bool);
	}

	/**
	 * @param array $statuses
	 */
	function status(array $statuses) {
		$this->dropField('status');
		if (sizeof($statuses)) {
			$this->appendIn('status', $statuses);
		}
	}

	/**
	 * @param int $from
	 * @param int $to
	 */
	function lastupdate($from = null, $to = null) {
		$this->dropField('lastupdatetime');
		$this->appendBetween('lastupdatetime', $from, $to);
	}

	/**
	 * @param array|string $team1
	 * @param array|string $team2
	 */
	function teams($team1, $team2 = null) {
		$this->dropField(array('hosts', 'guests'));
		$this->dropField('hosts');
		$this->dropField('guests');
		$team1 = (array) $team1;
		if (is_null($team2)) {
			$this->appendIn(array('hosts', 'guests'), $team1);
		} else {
			$team2 = (array) $team2;
			$this->appendIn('hosts', $team1);
			$this->appendIn('guests', $team2);
		}
	}
}