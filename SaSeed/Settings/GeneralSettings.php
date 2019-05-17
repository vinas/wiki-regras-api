<?php
/**
* General Settings
*
* This file holds basic settings for the whole application.
*
* @author Vinas de Andrade <vinas.andrade@gmail.com>
* @author Leandro Menezes
* @author Raphael Pawlik
* @since 2012/11/14
* @version 2.16.2027
* @license SaSeed\license.txt
*/

// DEBUG
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Timezone and regional Defitions
date_default_timezone_set('America/Sao_Paulo');
setlocale(LC_MONETARY, 'pt_BR');
setlocale(LC_ALL, 'Portuguese_Brazil.1252 ');

//Routes
require_once('Routes.php');

// Request Settings
define('contrlPos', 2);
