<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @version  3.2.8
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 * @link     http://laravel.com
 */


 // Gestion des erreurs
 function shutdown() {
 	$isError = false;
	
 	if ($error = error_get_last()){
 	switch($error['type']){
 	    case E_ERROR:
 	    case E_CORE_ERROR:
 	    case E_COMPILE_ERROR:
 	    case E_USER_ERROR:
 			 case E_PARSE:
 	        $isError = true;
 	        break;
 	    }
 	}
	
 	if ($isError){
 	    echo("<hr /> Une erreur est survenue. Merci de transmettre le message ci-dessous à un responsable compétent au sein du BdE.");
 			 exit(1);
 	}
 }
 register_shutdown_function('shutdown');


// --------------------------------------------------------------
// Tick... Tock... Tick... Tock...
// --------------------------------------------------------------
define('LARAVEL_START', microtime(true));

// --------------------------------------------------------------
// Indicate that the request is from the web.
// --------------------------------------------------------------
$web = true;

// --------------------------------------------------------------
// Set the core Laravel path constants.
// --------------------------------------------------------------
require '../paths.php';

// --------------------------------------------------------------
// Unset the temporary web variable.
// --------------------------------------------------------------
unset($web);

// --------------------------------------------------------------
// Launch Laravel.
// --------------------------------------------------------------
require path('sys').'laravel.php';