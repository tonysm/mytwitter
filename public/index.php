<?php

// app starts
session_start();

define("DS", DIRECTORY_SEPARATOR);
define("PUBLIC_DIR", __DIR__ . DS);
define("APP_DIR", dirname(PUBLIC_DIR) . DS . "src" . DS . "App" . DS);
define("VENDOR_DIR", dirname(PUBLIC_DIR) . DS . "vendor" . DS);
define("VIEWS_DIR", APP_DIR . "View" . DS);

// some app configuration of public directories
define("IMG_DIR", PUBLIC_DIR . "img" . DS);
define("CSS_DIR", PUBLIC_DIR . "css" . DS);
define("JS_DIR", PUBLIC_DIR . "js" . DS);

// autoload file
$loader = require_once VENDOR_DIR . "autoload.php";
use MyTwitter\Core\Request,
	MyTwitter\Core\Dispatcher;
try {
	$Request = new Request( $_GET );
	Dispatcher::process( $Request );
} catch(\Exception $e) {
	die($e->getMessage());
}
