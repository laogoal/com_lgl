<?php

class LGLModel extends JModelBase {
	function __construct() {
		parent::__construct();
		$this->populateState();
		if ($this->isInitialized()) {
			$this->leaguesInfo = $this->loadLocalLeaguesInfo();
			if ($this->isUpdatesEnabled()) {
				$remoteData = $this->loadRemoteInfo();
				if (is_array($remoteData)) {
					if (isset($remoteData['leagues']) && is_array($remoteData['leagues'])) {
						foreach ($remoteData['leagues'] as $row) {
							if (!isset($row['league_id'])) {
								continue;
							}
							$leagueId = $row['league_id'];
							if (isset($this->leaguesInfo[$leagueId])) {
								$this->leaguesInfo[$leagueId]['remote'] = true;
							}
						}
					}
					if (isset($remoteData['status'])) {
						$this->remoteInfo['status'] = $remoteData['status'];
					}
					if (isset($remoteData['expires'])) {
						$this->remoteInfo['expires'] = $remoteData['expires'];
						if ($this->remoteInfo['expires'] > time()) {
							$remoteData['status'] = 'expired';
						}
					}
				}
			}
		}
	}

	private $leaguesInfo = null;
	private $remoteInfo = null;
	private function populateState() {
		$params = JComponentHelper::getParams('com_lgl');
		$this->state->set('initialized', $params->get('initialized'));
		$this->state->set('updates_enabled', $params->get('updates_enabled'));
	}

	/**
	 * @return bool
	 */
	function isInitialized() {
		return (bool) $this->state->get('initialized');
	}

	/**
	 * @return bool
	 */
	function isUpdatesEnabled() {
		return (bool) $this->state->get('updates_enabled');
	}

	/**
	 * @return bool
	 */
	function isLocalhost() {
		$uri = JUri::getInstance();
		$host = $uri->getHost();
		if ($host && 'localhost' != $host) {
			return false;
		}
		return true;
	}

	private function loadLocalLeaguesInfo() {
		/** @var $db JDatabaseDriverMysqli */
		$db = JFactory::getDbo();
		$query = "
			SELECT l.league_id,
				   MAX(m.luts) luts,
				   COUNT(m.match_id) matches_count
			  FROM #__lgl_leagues l LEFT JOIN #__lgl_matches m USING(league_id)
		  GROUP BY l.league_id
		  ORDER BY l.league_id ASC
			";
		$db->setQuery($query);
		$leaguesData = $db->loadAssocList('league_id');

		$query = "
			SELECT league_id,
				   MAX(luts) luts,
				   COUNT(*) standings_count
			  FROM #__lgl_standings
		  GROUP BY league_id
		  ORDER BY league_id ASC
			";
		$db->setQuery($query);
		$standingsData = $db->loadAssocList('league_id');

		foreach (array_keys($leaguesData) as $leagueId) {
			if (!isset($standingsData[$leagueId])) {
				$leaguesData[$leagueId]['standings_count'] = 0;
				continue;
			}
			$leaguesData[$leagueId]['standings_count'] = $standingsData[$leagueId]['standings_count'];
			if ($standingsData[$leagueId]['luts'] > $leaguesData[$leagueId]['luts']) {
				$leaguesData[$leagueId]['luts'] = $standingsData[$leagueId]['luts'];
			}
		}
		return $leaguesData;
	}

	/**
	 * @return mixed|null
	 */
	public function getLeaguesInfo() {
		return $this->leaguesInfo;
	}

	private function loadRemoteInfo() {
		$client = LGLClient::getInstance();
		$data = null;
		try {
			$data = $client->sendCommand('Info');
		} catch (LGLClientException $x) {
			$data = false;
		}
		return $data;
	}

	/**
	 * @return null
	 */
	public function getRemoteInfo() {
		return $this->remoteInfo;
	}
}