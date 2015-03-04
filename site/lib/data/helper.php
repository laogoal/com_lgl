<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2014 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


/**
 * @author Мурат Эркенов <murat@11bits.net>
 */
class LGLDataHelper {

	static function pullLeaguesInfo() {
		$loadedLeagues = self::loadLeagues();
		if (!sizeof($loadedLeagues)) {
			return 0;
		}
		return self::saveLeagues($loadedLeagues);
	}

	static function pullUpdates() {
		$data = self::loadData('Updates');
		if (isset($data['matches']) && is_array($data['matches']) && sizeof($data['matches'])) {
			self::processMatches($data['matches']);
		}
		if (isset($data['standings']) && is_array($data['standings']) && sizeof($data['standings'])) {
			self::processStandings($data['standings']);
		}
	}

	/**
	 * @param array $matches
	 * @return int
	 * @throws LGLDataHelperException
	 */
	static function processMatches(array $matches) {
		$db = JFactory::getDbo();
		$chunks = array_chunk($matches, 100);
		$fieldsNames = array();
		$count = 0;
		while ($items = array_pop($chunks)) {
			$subQueries = array();
			foreach ($items as $item) {
				$match = array(
					'match_id' => $item['i'],
					'published' => !$item['h'],
					'league_id' => $item['l'],
					'hosts' => $item['t'][0],
					'guests' => $item['t'][1],
					'status' => $item['s']['s'],
					'details' => json_encode($item['f']),
					'sts' => $item['b'],
					'luts' => $item['z'],
					'score' => '0-0',
					'events' => '',
					'current' => '',
					'crc' => ''
				);
				if (isset($item['e'])) {
					$match['events'] = json_encode($item['e']);
				}

				if (isset($item['s']['c'])) {
					$match['score'] = implode('-', $item['s']['c']);
				}

				$current = array();
				if (isset($item['s']['o'])) {
					$current['period'] = $item['s']['o'];
				}
				if (isset($item['s']['b'])) {
					$current['cpsts'] = $item['s']['b'];
				}
				if (sizeof($current)) {
					$match['current'] = json_encode($current);
				}

				$match['crc'] = md5(json_encode($item));

				$subQueries[] = '(' . implode(', ', array_map(array($db, 'quote'), $match)) . ')';
				if (!sizeof($fieldsNames)) {
					$fieldsNames = array_keys($match);
				}
				$count++;
			}

			$query = 'INSERT INTO #__lgl_matches';
			$query .= ' (' . implode(', ', array_map(array($db, 'quoteName'), $fieldsNames)) . ')';
			$query .= ' VALUES ' . implode(', ', $subQueries);

			$query .= ' ON DUPLICATE KEY UPDATE ';
			$query .= implode(', ', array_map(function($str){
				$return = '`' . $str . '` = VALUES(`' . $str . '`)';
				if ('luts' == $str) {
					$return = '`luts` = GREATEST(`luts`, VALUES(`luts`))';
				}
				return $return;
			}, array_slice($fieldsNames, 1)));
			$db->setQuery($query);
			try {
				$db->execute();
			} catch (Exception $x) {
				throw new LGLDataHelperException('Can not save matches to database. ' . $x->getMessage());
			}
		}
		return $count;
	}

	/**
	 * @param array $standings
	 * @return int
	 * @throws LGLDataHelperException
	 */
	static function processStandings(array $standings) {
		$count = 0;
		$data = array();
		$leagueIds = array();
		foreach ($standings as $table) {
			$roundId = $table['r'];
			$leagueId = $table['l'];
			$leagueIds[] = $leagueId;
			foreach ($table['t'] as $row) {
				$detailsArr = array(
					'round' => $roundId
				);
				if (sizeof($row) > 9 && isset($row[9]['g'])) {
					$detailsArr['group'] = $row[9]['g'];
				}
				$data[] = array(
					'league_id' => $leagueId,
					'details' => $detailsArr,
					'position' => $row[0],
					'team' => $row[1],
					'points' => $row[2],
					'matches' => array(
						'played' => $row[3],
						'won' => $row[4],
						'drawn' => $row[5],
						'lost' => $row[6]
					),
					'goals' => array(
						'scored' => $row[7],
						'conceded' => $row[8]
					)
				);
			}
		}

		if (!sizeof($data)) {
			return $count;
		}

		$alreadySavedRows = array();
		$db = JFactory::getDbo();
		$query = "
			SELECT league_id, team
			  FROM #__lgl_standings
			  WHERE league_id IN('" . implode("', '", $leagueIds) . "')";
		$db->setQuery($query);
		$rows = (array) $db->loadAssocList();
		foreach ($rows as $row) {
			$alreadySavedRows[$row['league_id'] . '_' . $row['team']] = true;
		}

		$now = time();
		foreach ($data as $row) {
			$row['luts'] = $now;
			$row['details'] = json_encode($row['details']);
			$row['goals'] = json_encode($row['goals']);
			$row['matches'] = json_encode($row['matches']);
			$row = (object)$row;
			try {
				if (isset($alreadySavedRows[$row->league_id . '_' . $row->team])) {
					$db->updateObject('#__lgl_standings', $row, array('team', 'league_id'));
				} else {
					$db->insertObject('#__lgl_standings', $row);
				}
				$count++;
			} catch (Exception $x) {
				throw new LGLDataHelperException('Can not save data to database. ' . $x->getMessage());
			}
		}
		return $count;
	}

	/**
	 * @param string $cmd
	 * @throws LGLDataHelperException
	 * @return mixed
	 */
	private static function loadData($cmd) {
		$client = LGLClient::getInstance();
		try {
			$data = $client->sendCommand($cmd);
		} catch (LGLClientException $x) {
			throw new LGLDataHelperException(
				'Can not load data from provider. ' . $x->getMessage()
			);
		}
		return $data;
	}

	/**
	 * @return array
	 */
	private static function loadLeagues() {
		$loadedLeagues = array();
		$data = self::loadData('Info');
		if (isset($data['leagues']) && is_array($data['leagues'])) {
			foreach ($data['leagues'] as $league) {
				$itemToSave = array(
					'league_id' => $league['league_id'],
					'ets' => $data['expires'],
					'pstatus' => 'active'
				);
				if ('active' != $data['status']) {
					$itemToSave['pstatus'] = 'suspended';
				}
				$loadedLeagues[$league['league_id']] = $itemToSave;
			}
		}
		return $loadedLeagues;
	}

	/**
	 * @param $loadedLeagues
	 * @return int
	 */
	private static function saveLeagues(array $loadedLeagues) {
		/**
		 * @var $db JDatabaseDriverMysqli
		 */
		$db = JFactory::getDbo();
		$query = "SELECT league_id FROM #__lgl_leagues WHERE league_id IN ('" . implode("', '", array_keys($loadedLeagues)) . "')";
		$db->setQuery($query);
		$existingLeagues = $db->loadAssocList('league_id');
		$count = 0;
		foreach ($loadedLeagues as $leagueId => $leagueCfg) {
			$leagueCfg = (object)$leagueCfg;
			if (isset($existingLeagues[$leagueId])) {
				$count += $db->updateObject('#__lgl_leagues', $leagueCfg, 'league_id');
			} else {
				$leagueCfg->ustatus = true;
				$count += $db->insertObject('#__lgl_leagues', $leagueCfg);
			}
		}
		return $count;
	}
}

class LGLDataHelperException extends Exception {

}