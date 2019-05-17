<?php
/**
* This class handles CSS files
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/10/21
* @version 2.16.1031
* @license SaSeed\license.txt
*/

namespace SaSeed\Output;

Final class CSSInjector extends \SaSeed\Handlers\Files
{

	public static function declareGeneral()
	{
		self::declareFilesInFolder('');
	}

	public static function declareFilesInFolder($folder)
	{
		$files = parent::getFilesFromFolder(MainCssPath.$folder.DIRECTORY_SEPARATOR, 'js');
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

	private static function setTag($file)
	{
		return '<link href="'.WebCSSViewPath.parent::setFilePath($file).'" rel="stylesheet"/>'.PHP_EOL;
	}
}
