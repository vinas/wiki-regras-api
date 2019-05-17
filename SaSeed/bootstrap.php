<?php
/**
* Bootstrap
*
* This file loads basic Settings and starts up the right
* Controller for and Action Function.	
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @since 2012/11/15
* @version 1.16.1110
* @license SaSeed\license.txt
*/

namespace SaSeed;

header('Content-type: text/html; charset=UTF-8');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');

require_once('Settings'.DIRECTORY_SEPARATOR.'GeneralSettings.php'); // (Must be the first include)
require_once("autoload.php");

use SaSeed\Handlers\Requests;

function init() {
    $controller = '\Application\Controller\\'.Requests::getController();
    if (class_exists($controller)) {
        $obj = new $controller(Requests::getParams());
        $actionMethod = Requests::getActionFunction();
        if (!empty($actionMethod) && method_exists($obj, $actionMethod)) {
            $obj->$actionMethod();
            return;
        }
        throw New \Exception ("[SaSeed\bootstrap] - Required method is empty or does not exist in Controller.".PHP_EOL);
        return;
    }
    throw New \Exception ("[SaSeed\bootstrap] - Required Controller is empty or does not exist.".PHP_EOL);
}

init();
