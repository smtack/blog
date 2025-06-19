<?php
require_once "config.php";

spl_autoload_register(function($class) {
  require_once "core/" . $class . ".php";
});

require_once "libraries/functions.php";
require_once "libraries/session.php";
require_once "libraries/url.php";

// Error Reporting
ini_set("display_errors", "on");
ini_set("display_startup_errors", "on");
ini_set("log_errors", "on");

error_reporting(E_ALL);
// set_error_handler('errorHandler');

session_start();

$GLOBALS['allow_file_types'] = [
  'jpg', 'png', 'PNG', 'gif', 'webp'
];