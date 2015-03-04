<?php
jimport('joomla.filesystem.file');
require_once(JPATH_COMPONENT . '/controllers/cmd.php');

class InitController extends CmdController {

	/**
	 * @return bool
	 */
	protected function cmdSetStatus() {
		return $this->setParam('initialized', true);

	}

	/**
	 * @return array
	 * @throws CmdControllerException 
	 */
	protected function cmdGetLeagues() {
		$db = JFactory::getDbo();
		$query = "SELECT league_id FROM #__lgl_leagues";
		$db->setQuery($query);
		$leagues = (array) $db->loadColumn();
		return $leagues;
	}

	/**
	 * @param string $leagueId
	 * @return array
	 */
	protected function cmdInjectLeague($leagueId = null) {

		$result = array(
			'matches' => $this->doInjection(
				JPATH_ADMINISTRATOR . '/components/com_lgl/data/matches/' . $leagueId . '.json',
				'matches'
			),
			'standings' => 		$this->doInjection(
				JPATH_ADMINISTRATOR . '/components/com_lgl/data/standings/' . $leagueId . '.json',
				'standings'
			)
		);
		return $result;
	}

	/**
	 * @param $file
	 * @param $table
	 * @return bool
	 * @throws CmdControllerException 
	 */
	private function doInjection($file, $table) {
		if (!JFile::exists($file)) {
			throw new CmdControllerException ('Datafile ' . $file . ' not found');
		}
		$count = 0;
		$items = @json_decode(file_get_contents($file), true);
		if (is_array($items) && sizeof($items)) {
			if ('matches' == $table) {
				$count = LGLDataHelper::processMatches($items);
			}
			if ('standings' == $table) {
				$count = LGLDataHelper::processStandings($items);
			}
		}
		return $count;
	}
}
