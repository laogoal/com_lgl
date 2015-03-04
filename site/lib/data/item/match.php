<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

/**
 * @property string $id
 * @property string $published
 * @property string $league
 * @property string $hosts
 * @property string $guests
 * @property string $status
 * @property JData $current
 * @property JData $details
 * @property ArrayIterator $events
 * @property int $begintime
 * @property int $lastupdatetime
 * @property string $crc
 * @property string $score
 *
 */

class LGLDataItemMatch extends JData {

	/**
	 * @param array $arr
	 */
	function __construct($arr = array()) {
		$now = time();
		parent::__construct($arr);
		if (!empty($this->events)) {
			$events = array_map(function($arr){
				return new JData($arr);
			}, json_decode($this->events));
			$this->setProperty('events', new ArrayIterator($events));
		}

		$score = preg_split('/[^\d]/', $this->score);
		$this->setProperty('score', $score);


		$this->setProperty('details', new JData(json_decode($this->details, true)));
		$currentState = new JData(json_decode($this->current, true));
		if (in_array($currentState->period, array('1T', '2T')) && $currentState->cpsts > 0) {
			$minute = intval(($now - $currentState->cpsts) / 60);
			$injuredMinutes = $minute - 45;
			$minute = min($minute, 45);
			if ('2T' == $currentState->period) {
				$minute += 45;
			}
			if ($injuredMinutes > 0) {
				$minute .= "+";
			}
			$currentState->setProperty('minute', $minute);
		}
		$this->setProperty('current', $currentState);
		$this->setProperty('crc', md5(json_encode($this->jsonSerialize())));
	}
}