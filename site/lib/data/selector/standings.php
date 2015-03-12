<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



class LGLDataSelectorStandings extends  LGLDataSelector {
	function __construct() {
		$mapping = array(
			'team' => 'team',
			'league' => 'league_id',
			'position' => 'position',
			'points' => 'points',
			'goals' => 'goals',
			'matches' => 'matches',
			'details' => 'details',
			'lastupdatetime' => 'luts'
		);
		parent::__construct('LGLDataSetStandings', '#__lgl_standings', $mapping, JFactory::getDbo());
		$this->appendOrder('details');
		$this->appendOrder('position');
	}


	/**
	 * @param string $league
	 */
	function league($league) {
		$this->appendEq('league', $league);
	}

	/**
	 * @param int $from
	 * @param int $to
	 */
	function lastupdate($from = null, $to = null) {
		$this->appendBetween('lastupdatetime', $from, $to);
	}

	/**
	 * @param array|string $team
	 */
	function team($team) {
		$this->appendIn('team', (array) $team);
	}
}