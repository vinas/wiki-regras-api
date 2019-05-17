<?php
/**
* URL Requests Class
*
* This class contains functions that define which controller
* and action function are to be called.
* It also handles data sent thru GET or POST methods.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @author Leandro Menezes
* @since 2012/11/14
* @version 1.17.0607
* @license SaSeed\license.txt
*/

namespace SaSeed\Handlers;

Final class Requests
{

	/**
	* Gets Controller's name
	*
	* @return string
	*/
	public static function getController()
	{
		$params = self::getAllURLParams();
		return (empty($params[contrlPos])) ? 'CamposController' : $params[contrlPos].'Controller';
	}

	/**
	* Gets action Function's name
	*
	* @return string
	*/
	public static function getActionFunction()
	{
		$params = self::getAllURLParams();
		$pos = contrlPos + 1;
		return (!empty($params[$pos])) ? $params[$pos] : 'index';
	}

	/**
	* Gets all passed parameters
	*
	* This method checks all SaSeed html data functions
	* and returns the first set of data found, according to the
	* following priority: POST > Friendly URL > GET
	*
	* @return array
	*/
	public static function getParams()
	{
		$params = self::getPostParams();
		if ($params) {
			return $params;
		}
		$params = self::getURLParams();
		if ($params) {
			return $params;
		}
		return self::getGetParams();
	}

	/**
	* Gets all URL parameters
	*
	* @return array
	*/
	public static function getAllURLParams()
	{
		return explode('/', $_SERVER['REQUEST_URI']);
	}

	/**
	* Gets URL parameters
	*
	* This method gets all values contained in a friendly url
	* excluding controller's and action function's names
	*
	* @return array
	*/
	public static function getURLParams()
	{
		$params = [];
		$urlParams = self::getAllURLParams();
		for ($i = contrlPos + 2; $i < count($urlParams); $i++) {
			$params[] = $urlParams[$i];
		}
		return $params;
	}

	/**
	* Gets all parameters sent by POST method
	*
	* @return array
	*/
	private static function getPostParams()
	{
		return (count($_POST) > 0) ? $_POST : json_decode(file_get_contents('php://input'), true);
	}

	/**
	* Gets all parameters sent by GET method
	*
	* @return array
	*/
	private static function getGetParams()
	{
		return $_GET;
	}

}