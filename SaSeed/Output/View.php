<?php
/**
* View Class
*
* This class holds basic general functions to generate views
*
* @author ivonascimento <ivo@o8o.com.br>
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @author Leandro Menezes
* @author Raphael Pawlik
* @since 2012/11/14
* @version 1.16.1110
* @license SaSeed\license.txt
*/

namespace SaSeed\Output;

use SaSeed\Handlers\Exceptions;
use SaSeed\Output\JavaScriptInjector;
use SaSeed\Output\CSSInjector;

Final class View extends \SaSeed\Handlers\Files
{

	public static $data	= Array();
	public static $JSInjector;
	public static $CSSInjector;

	/**
	* Renders a template
	*
	* @param string
	*/
	public static function render($name)
	{
		if ($name) {
			self::$JSInjector = new JavaScriptInjector();
			self::$CSSInjector = new CSSInjector();
			if (self::templateFileExists($name)) {
				ob_start();
				extract(self::$data);
				require self::getTemplate($name);
				ob_end_flush();
				return;
			}
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Template file not found.'
			);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Template file not informed.'
		);
	}

	/**
	* Sets a variable into View context
	*
	* @param string
	* @param string
	*/
	public static function set($name = false, $value = false)
	{
		if ($name)
			self::$data[$name] = $value;
	}

	/**
	* Redirects user to root
	*/
	public static function gotoRoot()
	{
		View::redirect('/', true);
	}

	/**
	* Renders view buffer into a variable
	*
	* @param string - template
	* @param string
	*/
	public static function renderTo($name)
	{
		try {
			ob_start();
			if (self::templateFileExists($name)) {
				extract(self::$data);
				require self::getTemplate($name);
				$return	= ob_get_contents();
				ob_end_clean();
				return $return;
			}
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Template file not found'
			);
			return false;
		} catch (Exception $e) {
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Not possible to render: '.$e->getMessage()
			);
			return false;
		}
	}

	/**
	* Append html template to a html
	*
	* @param string - file name
	*/
	public static function appendTemplate($file)
	{
		echo self::renderTo($file);
	}

	/**
	* Easily redirect user
	*
	* @param string - template/url
	* @param boolean - true for external url, false for internal url
	*/
	public static function redirect($name = false, $full = false)
	{
		if ($name) {
			header('Location: '.(!$full) ? parent::setFilePath($name) : $name);
			return;
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'No destination URL given.'
		);
	}

	/**
	* Prints an array encoded in Json
	*
	* @param mixed
	*
	* @deprecated
	* @deprecated 1.16.11+
	* @deprecated There is a specific class to deal
	* @deprecated with JSON: SaSeed\Output\RestView.
	*/
	public static function renderJson($data) 
	{
		try {
			ob_start();
			echo json_encode($data);
			ob_end_flush();
		} catch (Exception $e) {
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Not possible to render json: '.$e->getMessage()
			);
		}
	}

	/**
	* Check if template file exists
	*
	* @param string - file name
	*/
	private static function templateFileExists($name)
	{
		if (file_exists(self::getTemplate($name)))
			return true;
		return false;
	}

	/**
	* Get template
	*
	* @param string - template name
	*/
	private static function getTemplate($name = false)
	{
		if ($name)
			return TemplatesPath . parent::setFilePath($name) . '.html';
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'No template given.'
		);
		return false;
	}

}