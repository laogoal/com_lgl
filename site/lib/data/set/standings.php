<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2013 - Murat Erkenov
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/


class LGLDataSetStandings extends LGLDataSet {

	/**
	 * @param array $item
	 * @return JData|LGLDataItemStandingsRow
	 */
	protected function transform(array $item) {
		return new LGLDataItemStandingsRow($item);
	}

	/**
	 * @var bool
	 */
	private $hasGroups = null;

	/**
	 * @return bool
	 * @throws LogicException
	 */
	function hasGroups() {
		if (is_null($this->hasGroups)) {
			if (!$this->count()) {
				throw new LogicException("This set has no items!. Load items first");
			}
			foreach ($this->getItems() as $item) {
				$this->hasGroups = false;
				if ($item->details->group) {
					$this->hasGroups = true;
				}
				break;
			}
		}
		return $this->hasGroups;
	}
}

