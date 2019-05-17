<?php
/**
* Autoload
*
* This lists and load necessary classes
*
* @author Leandro Menezes
* @since 2012/11/15
* @version 1.12.1115
* @license SaSeed\license.txt
*/

function _appautoload_($name) {
	$pathinfo		= pathinfo(dirname(__FILE__));
	$searchpath		= explode('\\', $name);
	$name			= array_pop( $searchpath );
	$searchpath		= $pathinfo['dirname'].DIRECTORY_SEPARATOR.implode(DIRECTORY_SEPARATOR,$searchpath).DIRECTORY_SEPARATOR;
	if (file_exists("{$searchpath}{$name}.php")) {
        require_once("{$searchpath}{$name}.php");
	}
}

spl_autoload_register('_appautoload_');
