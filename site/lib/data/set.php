<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );



abstract class LGLDataSet implements Countable, IteratorAggregate {


	/**
	 * @var array
	 */
	protected $data = array();


	function getIterator() {
		return new ArrayIterator($this->data);
	}

	/**
	 * @return int
	 */
	function count() {
		return sizeof($this->data);
	}

	function getItems() {
		return $this->data;
	}

	/**
	 * @param array $item
	 * @return JData
	 */
	protected function transform(array $item) {
		return new JData($item);
	}

	/**
	 * @param array $item
	 * @param null|string $id
	 */
	function addArrayItem(array $item, $id = null) {
		$this->addItem($this->transform($item), $id);
	}

	/**
	 * @param JData $item
	 * @param null|string $id
	 */
	function addItem(JData $item, $id = null) {
		if ($id) {
			$this->data[$id] = $item;
		} else {
			$this->data[] = $item;
		}
	}
}

