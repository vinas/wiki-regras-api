<?php
/**
* Ultils Class
*
* This class holds basic general functions to be called
* throughout the application.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @author Leandro Menezes
* @author Raphael Pawlik
* @since 2011/10/13
* @version 1.16.1027
* @license SaSeed\license.txt
*/

namespace SaSeed;

use SaSeed\Handlers\Exceptions;

class Utils
{

	/**
	* Converts an object to an array
	*
	* @param object
	* @return array
	* @throws new exception
	*/
	public static function objectToArray($obj)
	{
		if (is_object($obj)) {
			return get_object_vars($obj);
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Not a valid object.'
		);
	}

	/**
	* Converts an array to an object
	*
	* @param array
	* @return object
	* @throws new exception
	*/
	public static function arrayToObject($arr)
	{
		if (is_array($arr)) {
			$obj = new stdClass();
			foreach ($arr as $k => $v) {
			    $obj->$k = $v;
			}
			return $obj;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Not a valid array.'
		);
	}

	/**
	* Generates a random String
	*
	* @param integer - lenght
	* @return string
	* @throws new exception
	*/
	public static function randomString($len = 8)
	{
		if (len > 0) {
			$chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
			$lim = strlen($chars) - 1;
			for ($i = 0; $i < $len; $i++) {
				$str .= $chars[rand(0, $lim)];
			}
			return $str;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Invalid lenght.'
		);
	}

	/**
	* Formats date to MySql format
	*
	* @param string	- accepts "yyyy/mm/dd"
	* @throws new exception
	* @return string - "yyyy-mm-dd" 
	*/
	public static function toMySqlDate ($date = false)
	{
		if ((!$date) || ($date == 'now')) {
			return date("Y").'-'.date("m").'-'.date("d");
		}
		$d = substr($date, 8, 2);
		$m = substr($date, 5, 2);
		$y = substr($date, 0, 4);
		if (checkdate($m, $d, $y)) {
			return $y.'-'.$m.'-'.$d;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Invalid date.'
		);
	}

	/**
	* Formats US date to MySql format
	*
	* @param string	- accepts "mm/dd/yyyy"
	* @return string - "yyyy-mm-dd" 
	* @throws new exception
	*/
	public static function usDateToMySqlDate($date = false)
	{
		// Se data não enviada
		if ((!$date) || ($date == 'now')) {
			return date("Y").'-'.date("m").'-'.date("d");
		}
		$d = substr($date, 0, 2);
		$m = substr($date, 3, 2);
		$y = substr($date, 6, 4);
		if (checkdate($m, $d, $y)) {
			return $y.'-'.$m.'-'.$d;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Invalid date.'
		);
	}

	/**
	* Formats mySql date to regular date
	*
	* @param string	- accepts "yyyy-mm-dd"
	* @return string - "dd/mm/yyyy"
	* @throws new exception
	*/
	public static function mySqlDateToDate ($data = false)
	{
		// Se data não enviada
		if ((!$data) || ($data == 'now')) {
			return date("d").'/'.date("m").'/'.date("Y");
		}
		$d = substr($date, 8, 2);
		$m = substr($date, 5, 2);
		$y = substr($date, 0, 4);
		if (checkdate($m, $d, $y)) {
			return $d.'/'.$m.'/'.$y;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Invalid date.'
		);
	}

	/**
	* Formats mySql date to US date
	*
	* @param string	- accepts "yyyy-mm-dd"
	* @return string - "mm/dd/yyyy"
	* @throws new exception
	*/
	public static function mySqlDateToUsDate ($data = false)
	{
		// Se data não enviada
		if ((!$data) || ($data == 'now')) {
			return date("m").'/'.date("d").'/'.date("Y");
		}
		$d = substr($date, 8, 2);
		$m = substr($date, 5, 2);
		$y = substr($date, 0, 4);
		if (checkdate($m, $d, $y)) {
			return $m.'/'.$d.'/'.$y;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Invalid date.'
		);
	}
}
