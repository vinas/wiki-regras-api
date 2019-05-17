<?php
/**
* Query Model Class
*
* This class helps the developer to build string queries;
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/10/27
* @version 1.16.1110
* @license SaSeed\license.txt
*
*/

namespace SaSeed\Database;

class QueryModel
{
	private $select = false;
	private $from = false;
	private $where = false;
	private $orderBy = false;
	private $limit = false;
	private $max = false;

	public function setSelect($select)
	{
		$this->select = $select;
	}
	public function getSelect()
	{
		return ($this->select) ? $this->select : '*';
	}

	public function setFrom($from)
	{
		$this->from = $from;
	}
	public function getFrom()
	{
		return $this->from;
	}

	public function setWhere($where)
	{
		$this->where = $where;
	}
	public function getWhere()
	{
		return ($this->where) ? $this->where : '1';
	}

	public function setOrderBy($orderBy)
	{
		$this->orderBy = $orderBy;
	}
	public function getOrderBy()
	{
		return $this->orderBy;
	}

	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
	public function getLimit()
	{
		return $this->limit;
	}

	public function setMax($max)
	{
		$this->max = $max;
	}
	public function getMax()
	{
		return $this->max;
	}
}
