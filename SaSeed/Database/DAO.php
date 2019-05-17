<?php
/**
* DAO Class
*
* Someone willl eventually write a description here.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/09/02
* @version 1.16.1025
* @license SaSeed\license.txt
*/

namespace SaSeed\Database;

use SaSeed\Database\Database;
use SaSeed\Database\QueryBuilder;


class DAO
{

	/**
	* Set and connect to Database
	*
	* It loads database connection information from file SaSeed\Settings\Database.ini
	*
	* @param string - database name
	*/
	public function setDatabase($dbName)
	{
		$settings = parse_ini_file(SettingsPath.'database.ini', true);
		$db	= new Database();
		$db->connect(
			$settings[$dbName]['driver'],
			$settings[$dbName]['host'],
			$settings[$dbName]['dbname'],
			$settings[$dbName]['user'],
			$settings[$dbName]['password']
		);
		return $db;
	}

	public function setQueryBuilder()
	{
		return new QueryBuilder();
	}


}
