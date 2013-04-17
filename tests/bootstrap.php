<?php
error_reporting(-1);

define("DS", DIRECTORY_SEPARATOR);
define("PUBLIC_DIR", dirname(__DIR__) . DS . 'public' . DS);
define("APP_DIR", dirname(PUBLIC_DIR) . DS . "App" . DS);
define("VENDOR_DIR", dirname(PUBLIC_DIR) . DS . "vendor" . DS);
define("VIEWS_DIR", APP_DIR . "View" . DS);
define("CONTROLLERS_DIR", APP_DIR . "Controllers" . DS);

// some app configuration of public directories
define("IMG_DIR", PUBLIC_DIR . "img" . DS);
define("CSS_DIR", PUBLIC_DIR . "css" . DS);
define("JS_DIR", PUBLIC_DIR . "js" . DS);

// autoload file
$loader = require_once VENDOR_DIR . "autoload.php";
