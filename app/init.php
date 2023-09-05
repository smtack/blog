<?php
require_once "config.php";

spl_autoload_register(function($class) {
  require_once "core/" . $class . ".php";
});

require_once "libraries/functions.php";
require_once "libraries/session.php";
require_once "libraries/url.php";

ini_set("display_errors", 'on');
error_reporting(E_ALL);

$self = escape($_SERVER['PHP_SELF']);