<?php
/**
* QueryBuilder Class
*
* This class helps the developer to build string queries;
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/10/25
* @version 1.16.1111
* @license SaSeed\license.txt
*
*/

namespace SaSeed\Database;

use SaSeed\Handlers\Exceptions;
use SaSeed\Database\QueryModel;

class QueryBuilder
{
	private $query;
	private $mainTable;
	private $mainTableAlias;
	private $cols;
	private $join;
	private $conditions;


	public function __construct()
	{
		$this->query = new QueryModel();
	}

	public function getQuery()
	{
		return $this->query;
	}

	public function getMainTableAlias()
	{
		return $this->mainTableAlias;
	}

	/**
	* Set the columns to be selected manually straight to the query 
	*
	* @param string
	* @return void
	* @throws new exception
	*/
	public function rawSelect($select = false)
	{
		if ($this->isRawInputValid($select)) {
			$this->query->setSelect($select);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Empty or invalid raw value. This will accept only strings.'
		);
	}

	/**
	* Set source table to the query manually
	*
	* @param string
	* @return void
	* @throws new exception
	*/
	public function rawFrom($from = false)
	{
		if ($this->isRawInputValid($from)){
			$this->query->setFrom($from);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Empty or invalid raw value. This will accept only strings.'
		);
	}

	/**
	* Set condition clauses to the query manually
	*
	* @param string
	* @return void
	* @throws new exception
	*/
	public function rawWhere($where = false)
	{
		if ($this->isRawInputValid($where)) {
			$this->query->setWhere($where);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Empty or invalid raw value. This will accept only strings.'
		);
	}

	/**
	* Set query limit manually
	*
	* @param integer
	* @return void
	* @throws new exception
	*/
	public function setLimit($limit = false)
	{
		if (isNumberInputValid($limit)) {
			$this->query->setLimit($limit);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Empty or invalid raw value. This will accept only integers bigger than zero.'
		);
	}

	/**
	* Set query max manually
	*
	* @param integer
	* @return void
	* @throws new exception
	*/
	public function setMax($max = false)
	{
		if (isNumberInputValid($max)) {
			$this->query->setMax($max);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Empty or invalid raw value. This will accept only integers bigger than zero.'
		);
	}

	/**
	* Define table columns to be selected
	*
	* @param string
	*        [colName1, colName2,...]
	*        [[colName1, colAlias1], [colName2, colAlias2],...]
	*        [[colName1, colAlias1, tableAlias], [colName2, colAlias2, tableAlias],...]
	* @return void
	* @throws new exception
	*/
	public function select($cols)
	{
		if ($cols) {
			$this->addColumnsToSelect($cols);
			$this->query->setSelect($this->cols);
			return;
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: No columns sent.');
	}

	/**
	* Define data source
	*
	* @param string
	* @param string
	* @return void
	*/
	public function from($table, $alias = false)
	{
		$this->mainTable = $table;
		$this->mainTableAlias = ($alias) ? $alias : 'mainTable';
		$this->query->setFrom($this->mainTable.' AS '.$this->mainTableAlias);
	}

	/**
	* Define adjoined data sources
	*
	* @param [colName, colAlias]
	* @param string
	* @param string
	* @param string
	* @param string
	* @return void
	* @throws new exception
	*/
	public function join($joinTable, $joinCol, $comp, $compCol, $compAlias = false)
	{
		if (!$this->mainTable) {
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Error: You must declare the main query table before you can declare joins.'
			);
			return;
		}
		if (is_array($joinTable)) {
			$this->join = ' INNER JOIN '.$joinTable[0].' AS '.$joinTable[1].' ON '.$joinTable[1].'.'.$joinCol.' '.$comp;
			$this->join .= ($compAlias) ? $compAlias : $this->mainTableAlias;
			$this->join .= '.'.$compCol;
			$this->query->setFrom($this->query->getFrom().$this->join);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Invalid table. First argument (joined table) must be sent as an array: [name, alias].'
		);
	}

	/**
	* Define where condition clauses
	*
	* Once this method is called, it will add AND logical operator to every other call.
	* For the OR operator, use the function orWhere();
	*
	* @param [colName, comparator, value, opt tableAlias]
	* @return void
	* @throws new exception
	*/
	public function where($clause)
	{
		if (is_array($clause) && $clause[0] && $clause[1] && $clause[2]) {
			$this->conditions = ($this->conditions) ? $this->conditions.' AND ' : '';
			if (isset($clause[3]))
				$this->conditions .= $clause[3].'.';
			$this->conditions .= $clause[0]." ".$clause[1]." '".$clause[2]."'";
			$this->query->setWhere($this->conditions);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Invalid where clause. It must be sent as an array: [colName, comparator, value, opt tableAlias].'
		);
	}

	/**
	* Define where condition clauses using OR logical operator
	*
	* @param [colName, comparator, value, opt tableAlias]
	* @return void
	* @throws new exception
	*/
	public function orWhere($clause)
	{
		if ($this->conditions) {
			if (is_array($clause) && $clause[0] && $clause[1] && $clause[2]) {
				$this->conditions .= ' OR ';
				if ($clause[3])
					$this->conditions .= $clause[3].'.';
				$this->conditions .= $clause[0]." ".$clause[1]." '".$clause[2]."'";
				$this->query->setWhere($this->conditions);
				return;
			}
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Error: Invalid where clause. It must be sent as an array: [colName, comparator, value, opt tableAlias].'
			);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: Cannot be the first condition declared. Use method where() instead.'
		);
	}

	/**
	* Defines a field to order by
	*
	* @param string
	* @param string
	* @return void
	* @throws new exception
	*/
	public function orderBy($column, $alias = false)
	{
		if ($column) {
			$this->query->setOrderBy(
				($alias) ? $alias.'.'.$column : $column
			);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Error: No column name received.'
		);
	}

	/**
	* Returns the query as a string
	*
	* @return string
	*/
	public function getQueryAsString()
	{
		$strQuery = 'SELECT '.$this->query->getSelect().' FROM '.$this->query->getFrom().' WHERE '.$this->query->getWhere();
		$limit = $this->query->getLimit();
		$max = $this->query->getMax();
		if ($limit) {
			$strQuery .= ' LIMIT '.$limit;
			if ($max) {
				$strQuery .= ', '.$max;
			}
		}
		return $strQuery;
	}

	private function addColumnsToSelect($cols)
	{
		if ($cols == '*') {
			$this->cols = '*';
			return;
		}
		if (is_string($cols)) {
 			$this->cols = ($this->cols) ? $this->cols.', ' : '';
			$this->cols .= ($this->mainTableAlias) ? $this->mainTableAlias.'.'.$cols : $cols;
			return;
		}
		if (is_array($cols)) {
			foreach ($cols as $col) {
				$this->cols = ($this->cols) ? $this->cols.', ' : '';
				if (is_array($col)) {
					if (count($col) == 2) {
						$this->cols .= $col[0] . ' AS ' .$col[1];
						continue;
					} else if (count($col) == 3) {
						$this->cols .= $col[2].'.'.$col[0] . ' AS ' .$col[1];
						continue;
					}
				}
				$this->cols .= $col;
			}
			return;
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Invalid columns sent.');
	}

	private function isRawInputValid($input)
	{
		return (($input) && is_string($input) && $input != '') ? true : false;
	}

	private function isNumberInputValid($input)
	{
		return (($input) && is_int($input) && $input > 0) ? true : false;
	}
}
