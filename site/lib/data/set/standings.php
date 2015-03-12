<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



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

