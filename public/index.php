<?php

use Core\App;

require '../vendor/autoload.php';

require "../config/config.php";

require "../app/libraries/functions.php";
require "../app/libraries/session.php";
require "../app/libraries/url.php";

// Error Reporting
ini_set("display_errors", "on");
ini_set("display_startup_errors", "on");
ini_set("log_errors", "on");
error_reporting(E_ALL);

session_start();

$GLOBALS['allow_file_types'] = [
    'jpg', 'jpeg', 'png', 'PNG', 'gif', 'webp'
];

$blog = new App();