<?php
/**
* General Routes
*
* This file holds basic route settings for the whole application.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2015/08/28
* @version 1.16.1108
* @license SaSeed\license.txt
*/

// WEB CONTEXT ROUTES
define('WebJSViewPath', 'js/');
define('WebCSSViewPath', 'css/');
define('WebImgViewPath', 'img/');

// LOCAL ROUTES
$path = dirname(__FILE__);
define('BasePath', substr($path, 0, strpos($path, 'SaSeed')));
define('SettingsPath', BasePath.'SaSeed'.DIRECTORY_SEPARATOR.'Settings'.DIRECTORY_SEPARATOR);
define('ViewPath', BasePath.'public_html'.DIRECTORY_SEPARATOR);
define('ImgPath', ViewPath.'img'.DIRECTORY_SEPARATOR);
define('TemplatesPath', ViewPath.'templates'.DIRECTORY_SEPARATOR);
define('MainJsPath', ViewPath.'js'.DIRECTORY_SEPARATOR);
define('MainCssPath', ViewPath.'css'.DIRECTORY_SEPARATOR);
