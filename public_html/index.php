<?php

date_default_timezone_set('Europe/Moscow');
$start = microtime(true);

/**
 * index.php in application entry point
 */
ini_set('display_errors', 1);
// set dev or production mode
define('DEBUG',true);
 //Check php version 5.4 only
if (version_compare(phpversion(), '5.4.0', '<') == true) { die ('PHP 5.4 Only'); }

// Define directory separator for different web servers based on Windows or Linux
define ('DIRSEP', DIRECTORY_SEPARATOR);
define('ROOTDIR', realpath('../'));

// include Autoload
include('../vendor/autoload.php'); // Autoload  PSR-4 only
// Start application...


\isv\IS::app()->start();

//\isv\IS::app()->start();
$time = microtime(true) - $start;
if(DEBUG)
    printf('<span style="position: fixed; bottom: 0;right: 0">Scripting time %.4F sec.', $time.'</span>');