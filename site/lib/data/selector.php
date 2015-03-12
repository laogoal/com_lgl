<?php
/**
 * @package LaoGoaL Core component for Joomla 3
 * @author Murat Erkenov (murat@11bits.net)
 * @copyright (C) 2015 - Murat Erkenov
 * @license     GNU General Public License version 2 or later; see license.txt
**/
defined( '_JEXEC' ) or die( 'Restricted access' );




abstract class LGLDataSelector {

	/**
	 * @var array
	 * @desc
	 */
	protected $mapping = array();

	/**
	 * @var string
	 * @desc class name of iterator returned by <b>select</b> method
	 */
	protected $resultsetClass;


	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var JDatabaseDriver
	 */
	protected $db;

	/**
	 * @var array
	 */
	protected $criterias = array();


	/**
	 * @var array
	 */
	protected $order = array();

	/**
	 * @param string $classname
	 * @param string $tablename
	 * @param array $mapping
	 * @param JDatabaseDriver $driver
	 */
	function __construct($classname, $tablename, array $mapping, JDatabaseDriver $driver = null) {
		$this->mapping = $mapping;
		$this->table = $tablename;
		$this->resultsetClass = $classname;
		if ($driver) {
			$this->db = $driver;
		}
		if (!$this->db) {
			$this->db = JFactory::getDbo();
		}
	}

	function dropField($key) {
		$key = (array) $key;
		$ckey = implode('+', $key);
		if (isset($this->criterias[$ckey])) {
			unset($this->criterias[$ckey]);
		}
	}

	/**
	 * @param string|array  $key
	 * @param string $sql
	 * @throws LGLDataSelectorException
	 */
	function appendField($key, $sql) {
		$key = (array) $key;
		$ckey = implode('+', $key);
		if (!isset($this->criterias[$ckey])) {
			$this->criterias[$ckey] = array();
		}
		foreach ($key as $k) {
			if (!isset($this->mapping[$k])) {
				throw new LGLDataSelectorException("Key $k not defined in mapping");
			}
			$this->criterias[$ckey][] = sprintf($sql, $this->mapping[$k]);
		}
	}

	function appendEq($key, $value) {
		$this->appendField($key, "`%s` = '" . $this->db->escape($value) . "'");
	}

	function appendIn($key, array $values) {
		$values = array_map(array($this->db, 'escape'), $values);
		if (sizeof($values) > 1) {
			$this->appendField($key, "`%s` IN('" . implode("', '", $values) . "')");
		} else {
			$this->appendField($key, "`%s` = '" . array_pop($values) . "'");
		}
	}

	/**
	 * @param $key
	 * @param $from
	 * @param $to
	 * @return bool
	 */
	function appendBetween($key, $from, $to) {
		$from = (int) $from;
		$to = (int) $to;
		if (!(($from + $to) > 0)) {
			return false;
		}
		if ($from > 0 && $to > 0) {
			$this->appendField($key, "`%s` BETWEEN  $from AND $to");
			return true;
		}
		if ($from > 0) {
			$this->appendField($key, "`%s` >=  $from");
		}
		if ($to > 0) {
			$this->appendField($key, "`%s` <=  $to");
		}
		return true;
	}


	/**
	 * @param string $key
	 * @param string $order
	 */
	function appendOrder($key, $order = 'ASC') {
		if (!isset($this->mapping[$key])) {
			throw new LGLDataSelectorException("Key $key not defined in mapping");
		}
		$this->order[$this->mapping[$key]] = $order;
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @return LGLDataSet
	 */
	function select($limit = 0, $offset = 0) {
		$query = $this->db->getQuery(true);
		$query->select($this->mapping)->from($this->table);
		if (sizeof($this->criterias)) {
			$where = array();
			foreach ($this->criterias as $criteria) {
				if (sizeof($criteria) > 1) {
					$where[] = '(' . implode(' OR ', $criteria) . ')';
				} else {
					$where[] = array_pop($criteria);
				}
			}
			$query->where($where);
		}
		if ($this->order) {
			$order = array();
			foreach ($this->order as $k => $v) {
				$order[] = "$k $v";
			}
			$query->order($order);
		}
		$this->db->setQuery($query, $offset, $limit);
		$idKey = null;
		if (isset($this->mapping['id'])) {
			$idKey = $this->mapping['id'];
		}

		/**
		 * @var LGLDataSet $resultSet
		 */
		$resultSet = new $this->resultsetClass();
		foreach ($this->db->loadAssocList() as $row) {
			$resultSet->addArrayItem($this->doMapping($row), ($idKey)?$row[$idKey]:null);
		}
		return $resultSet;
	}

	/**
	 * @param array $row
	 * @return array
	 * @throws LGLDataSelectorException
	 */
	private function doMapping(array $row) {
		$obj = array();
		foreach ($this->mapping as $objKey => $rowKey) {
			if (isset($row[$rowKey])) {
				$obj[$objKey] = $row[$rowKey];
			}
		}
		return $obj;
	}
}


class LGLDataSelectorException extends Exception {

}