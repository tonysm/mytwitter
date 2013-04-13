<?php

// app starts
session_start();

define("DS", DIRECTORY_SEPARATOR);
define("PUBLIC_DIR", __DIR__ . DS);
define("APP_DIR", dirname(PUBLIC_DIR) . DS);
define("VENDOR_DIR", APP_DIR . "vendor" . DS);

// some app configuration of public directories
define("IMG_DIR", PUBLIC_DIR . "img" . DS);
define("CSS_DIR", PUBLIC_DIR . "css" . DS);
define("JS_DIR", PUBLIC_DIR . "js" . DS);

// autoload file
$loader = require_once dirname(__DIR__) . DS . "vendor" . DS . "autoload.php";
use MyTwitter\Core\Request,
	MyTwitter\Core\Dispatcher;
try {
	$Request = new Request( $_GET );
	Dispatcher::process( $Request );
} catch(\Exception $e) {
	die($e->getMessage());
}
