<?php
/**
* This class handles JavaScript files
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/10/21
* @version 2.16.1031
* @license SaSeed\license.txt
*/

namespace SaSeed\Output;

Final class JavaScriptInjector extends \SaSeed\Handlers\Files
{

	public static function declareGeneral()
	{
		self::declareFilesInFolder('general');
	}

	public static function declareLibs()
	{
		self::declareFilesInFolder('libs');
	}

	public static function declareFilesInFolder($folder)
	{
		$files = parent::getFilesFromFolder(MainJsPath.$folder.DIRECTORY_SEPARATOR, 'js');
		if ($files) {
			foreach ($files as $file) {
				echo self::setTag($folder.'/'.$file);
			}
		}
	}

	public static function declareSpecific($file)
	{
		echo self::setTag(parent::setFilePath($file).'.js');
	}

	private static function setTag($fileName)
	{
		return '<script type="text/javascript" src="'.WebJSViewPath.parent::setFilePath($fileName).'"></script>'.PHP_EOL;
	}
}
