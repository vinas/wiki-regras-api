<?php
/**
* Exception Handling Class
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2016/09/01
* @version 1.16.1110
* @license SaSeed\license.txt
*/

namespace SaSeed\Handlers;

Final class Exceptions
{

	/**
	* Throws a system exception
	*
	* @param string
	* @param string
	* @param exception
	* @throws exception
	*/
	public static function throwing($class, $method, $err)
	{
		throw('['.$class.'::'.$method.'] - '.$err->getMessage().PHP_EOL);
	}

	/**
	* Throws an application error
	*
	* @param string
	* @param string
	* @param string
	* @throws new exception
	*/
	public static function throwNew($class, $method, $msg)
	{
		throw New \Exception ("[".$class."::".$method."] - ".$msg.PHP_EOL);
	}
}
