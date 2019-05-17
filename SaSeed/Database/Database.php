<?php
/**
* PDO Database Class
*
* This file holds basic Database functions for the whole
* Framework. It was adapted from an old class originally built
* to work with mysql only.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/04/16
* @version 2.16.1110
* @license SaSeed\license.txt
*
* @todo This needs a complete documentation and refactor.
*/

namespace SaSeed\Database;

use \PDO;
use SaSeed\Handlers\Exceptions;

class Database
{

	private $connection;
	private $isLocked = false;

	/**
	* Connects to the Database
	*
	* @param string
	* @param string
	* @param string
	* @param string
	* @param string
	* @param string
	* @return mixed
	* @throws PDOException
	*/
	public function connect($driver, $host, $dbName, $user, $pass, $charset = 'utf8')
	{
		try {
			$this->connection = new PDO($driver.':host='.$host.';dbname='.$dbName.';charset='.$charset, $user, $pass);
			$this->setConnectionAttributes();
			return $this->connection;
		} catch (PDOException $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		}
		return false;
	}

	/**
	* Closes a Database connection
	*
	* @return void
	*/
	public function close()
	{
		$this->connection = null;
	}

	/**
	* Returns last inserted id
	*
	* @return integer
	*/
	public function lastId() {
		return $this->connection->lastInsertId();
	}

	/**
	* Get rows and return them as an associative array
	*
	* @param object
	* @return array
	* @throws Exception
	*/
	public function getRows($saSeedQuery)
	{
		$sel = $saSeedQuery->getSelect();
		$from = $saSeedQuery->getFrom();
		$where = $saSeedQuery->getWhere();
		$orderBy = $saSeedQuery->getOrderBy();
		if ($sel && $from && $where) {
			try {
				$limit = $saSeedQuery->getLimit();
				$max = $saSeedQuery->getMax();
				$query = 'SELECT '.$sel.' FROM '.$from.' WHERE '.$where;
				if ($orderBy)
					$query .= ' ORDER BY '.$orderBy;
				if ($limit) {
					$query .= ' LIMIT '.$limit;
					if ($max) {
						$query .= ', '.$max;
					}
				}
				$stmt = $this->runQuery($query);
				return ($stmt) ? $this->fetchAssoc($stmt) : [];
			} catch (Exception $e) {
				Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
				return [];
			}
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Invalid query.');
		return [];
	}

	/**
	* Gets a single row and returns it as an associative array.
	*
	* If more than one row is returned, this method will return
	* the first row on the result set.
	*
	* @param object
	* @return array
	* @throws Exception
	*/
	public function getRow($saSeedQuery)
	{
		$sel = $saSeedQuery->getSelect();
		$from = $saSeedQuery->getFrom();
		$where = $saSeedQuery->getWhere();
		if ($sel && $from && $where) {
			try {
				$query = 'SELECT '.$sel.' FROM '.$from.' WHERE '.$where.' LIMIT 1';
				$result = $this->fetchAssoc($this->runQuery($query));
				return (count($result) == 1) ? $result[0] : [];
			} catch (Exception $e) {
				Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
				return [];
			}
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Invalid query.');
		return [];
	}

	/**
	* Updates one or more rows
	*
	* @param string
	* @param array
	* @param array
	* @param array
	* @return void
	* @throws Exception
	*/
	public function update($table, $values, $fields, $condition)
	{
		if (
			count($fields) == count($values)
			&& is_array($condition)
			&& !empty($condition[0])
			&& !empty($condition[1])
			&& !empty($condition[2])
		) {
			$query = 'UPDATE '.$table.' SET ';
			for ($i = 0; $i < count($fields); $i++) {
				if ($i != 0) {
					$query .= ', ';
				}
				$query .= $fields[$i].' = ';
				if (is_numeric($values[$i])) {
					$query .= $values[$i];
				} else {
					$query .= "'".$values[$i]."'";
				}
			}
			$query .= ' WHERE '.$condition[0].' '.$condition[1].' '.$condition[2];
			$this->runQuery($query);
			return;
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Invalid parameters.');
	}

	/**
	* Deletes one or more rows
	*
	* @param string
	* @param array
	* @throws Exception
	*/
	public function deleteRow($table, $condition = false)
	{
		if ($condition && is_array($condition)) {
			$this->runQuery('DELETE FROM '.$table.' WHERE '.$condition[0].' '.$condition[1].' '.$condition[2]);
		} else {
			Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Second argument (condition) must be an array: [column, comparator, value]');
		}
	}

	/**
	* Inserts one row
	*
	* @param string
	* @param array
	* @param array
	* @return void
	* @throws Exception
	*/
	public function insertRow($table, $values, $fields = false)
	{
		if (!empty($table)) {
			$query = 'INSERT INTO '.$table.' (';
			if ($fields == false) {
				$fields = $this->listFieldsNoId($table);
			}
			if (count($fields) == count($values)) {
				for ($i = 0; $i < count($fields); $i++) {
					if ($i != 0) {
						$query .= ', ';
					}
					$query .= $fields[$i];
				}
				$query .= ') VALUES (';
				for ($i = 0; $i < count($values); $i++) {
					if ($i != 0) {
						$query .= ', ';
					}
					if (is_numeric($values[$i])) {
						$query .= $values[$i];
					} else {
						$query .= "'".$values[$i]."'";
					}
				}
				$query .= ')';
				$this->runQuery($query);
				return;
			}
			Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Amount of columns and values given do not match.');
			return;
		}
		Exceptions::throwNew(__CLASS__, __FUNCTION__, 'Error: Invalid table name.');
	}

	/**
	* Returns names of the fields in a table
	*
	* @param string
	* @return array
	*/
	public function listFields($table, $id = true)
	{
		$fields = [];
		$query = 'SHOW COLUMNS FROM '.$table;
		$rows = $this->fetchAssoc($this->runQuery($query));
		foreach ($rows as $row) {
			if ($row['Field'] == 'id' && !$id) {
				continue;
			}
			$fields[] = $row['Field'];
		}
		return $fields;
	}

	/**
	* Returns names of the fields in a table, without the ID field
	*
	* @param string
	* @return array
	*/
	public function listFieldsNoId($table)
	{
		return $this->listFields($table, false);
	}

	/**
	* Counts the columns in a table and returns it.
	*
	* @param string
	* @return integer
	*/
	public function countFields($table)
	{
		return count($this->fetchAssoc($this->runQuery('DESCRIBE '.$table)));
	}

	/**
	* Returns the total of rows affected by the last query
	*
	* @return integer
	*/
	public function affectedRows()
	{
		return $this->connection->rowCount();
	}

	/**
	* Locks tables
	*
	* @param array
	* @return boolean
	*/
	public function lockTables($tables)
	{
		if ((is_array($tables)) && (count($tables) > 0)) {
			$msql = '';
			foreach ($tables as $name=>$type){
				$msql .= (!empty($msql)?', ':'').''.$name.' '.$type.'';
			}
			$this->runQuery('LOCK TABLES '.$msql.'');
			$this->isLocked = true;
			return true;
		}
		return false;
	}

	/**
	* Unlocks tables
	*
	* @param array
	* @return boolean
	*/
	public function unlockTables()
	{
		if ($this->isLocked){
			$this->runQuery('UNLOCK TABLES');
			$this->isLocked = false;
			return true;
		}
		return false;
	}


	/**
	* Runs a Query
	*
	* @param string
	* @return result set
	* @throws PDOException
	* @throws Exception
	*/
	private function runQuery($query)
	{
		$res = null;
		try{
			$res = $this->connection->query($query);
		} catch (PDOException $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		} catch (Exception $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		} finally {
			return $res;
		}
		
	}

	/**
	* Fetches data in a result set and returns it in asked format
	*
	* @param result set
	* @param string
	* @return array
	* @throws PDOException
	*/
	private function fetch($stmt, $mode)
	{
		try {
			return $stmt->fetchAll($mode);
		} catch (PDOException $e) {
			Exceptions::throwing(__CLASS__, __FUNCTION__, $e);
		}
		
	}

	/**
	* Returns a result set as an associative array
	*
	* @param result set
	* @return array
	*/
	private function fetchAssoc($stmt)
	{
		return $this->fetch($stmt, PDO::FETCH_ASSOC);
	}

	/**
	* Returns a result set as an numeric array
	*
	* @param result set
	* @return array
	*/
	private function fetchNumeric($stmt)
	{
		return $this->fetch($stmt, PDO::FETCH_NUM);
	}

	/**
	* Returns a result set as an object
	*
	* @param result set
	* @return object
	*/
	private function fetchObject($stmt)
	{
		return $this->fetch($stmt, PDO::FETCH_OBJ);
	}

	/**
	* Returns a result set as an array indexed by both
	* column name and 0-indexed column number
	*
	* @param result set
	* @return object
	*/
	private function fetchBoth($stmt)
	{
		return $stmt->fetch($stmt, PDO::FETCH_BOTH);
	}

	/**
	* This is called when a database connection is created.
	*
	* All connection attributes you set here will be autimatically set
	* when a connection is created. 
	*
	* @return void
	*/
	private function setConnectionAttributes() {
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
}
