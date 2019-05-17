<?php
/**
* This class handles files
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/10/21
* @version 2.16.1108
* @license SaSeed\license.txt
*/

namespace SaSeed\Handlers;

use SaSeed\Handlers\Exceptions;

class Files
{

	public static function txtToArray($file)
	{
		return $this->fileToArray($file);
	}

	public static function iniToArray($folder = false, $file = false)
	{
		try {
			if (($folder) && ($file) && $folder != '' && $file != '') {
				return parse_ini_file(BasePath.parent::setFilePath($file), true);
			}
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Forder and file name must be informed.'
			);
			return false;
		} catch (Exception $e) {
			Exceptions::throwing(
				__CLASS__,
				__FUNCTION__,
				'File could not be loaded.'
			);
			return false;
		}
	}

	public static function fileToArray($file)
	{
		try {
			if (($folder) && $folder != '') {
				return file(BasePath.parent::setFilePath($file));
			}
			Exceptions::throwNew(
				__CLASS__,
				__FUNCTION__,
				'Forder not informed.'
			);
			return false;
		} catch (Exception $e) {
			Exceptions::throwing(
				__CLASS__,
				__FUNCTION__,
				'File could not be loaded.'
			);
			return false;
		}
	}

	public static function xmlToArray($file)
	{
		try {
			$xml = simplexml_load_string(
					file_get_contents(parent::setFilePath($file)),
					'SimpleXMLElement',
					LIBXML_NOCDATA
				);
			return json_decode(json_encode($xml), TRUE);
		} catch (Exception $e) {
			Exceptions::throwing(
				__CLASS__,
				__FUNCTION__,
				'File could not be loaded.'
			);
			return false;
		}
	}

	public static function xmlToObject($file)
	{
		try {
			return simplexml_load_string(
					file_get_contents(parent::setFilePath($file))
				);
		} catch (Exception $e) {
			Exceptions::throwing(
				__CLASS__,
				__FUNCTION__,
				'File could not be loaded.'
			);
			return false;
		}
	}

	/**
	* Get all files' names in a folder given folder. The second
	* parameter will serve as a filter for file extensions.
	*
	* @param string
	* @param string
	* @return array
	*/
	public static function getFilesFromFolder($folder, $ext = false)
	{
		if (($folder) && $folder != '') {
			$files = scandir($folder);
			if (count($files) > 2) {
				if ($ext) {
					$res = [];
					for ($i = 0; $i < count($files); $i++) {
						$pathInfo = pathinfo($folder.$files[$i]);
						if ($pathInfo['extension'] == $ext)
							$res[] = $files[$i];
					}
					return $res;
				}
				unset($files[0]);
				unset($files[1]);
				return array_slice($files, 2);
			}
		}
		Exceptions::throwNew(
			__CLASS__,
			__FUNCTION__,
			'Forder not informed.'
		);
		return false;
	}

	/**
	* format file's path
	*
	* @param string - file name and path
	*/
	public static function setFilePath($file)
	{
		return str_replace('_','/', $file);
	}

	/**
	* Compress given file content
	*
	* @param string - content
	* @param string - compressed content
	*/
	private static function compress($buffer)
	{
		// remove comments
		$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
		// remove tabs, spaces, newlines, etc.
		$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  '), '', $buffer);
		// remove unnecessary spaces.
		$buffer = str_replace('{ ', '{', $buffer);
		$buffer = str_replace(' }', '}', $buffer);
		$buffer = str_replace('; ', ';', $buffer);
		$buffer = str_replace(', ', ',', $buffer);
		$buffer = str_replace(' {', '{', $buffer);
		$buffer = str_replace('} ', '}', $buffer);
		$buffer = str_replace(': ', ':', $buffer);
		$buffer = str_replace(' ,', ',', $buffer);
		$buffer = str_replace(' ;', ';', $buffer);
		return $buffer;
	}

}