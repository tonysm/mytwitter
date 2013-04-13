<?php

namespace App\Controllers\Components;

class Session {

	public function __construct() {
	}

	public function write($key, $value) {
		$_SESSION[$key] = $value;
	}

	public function read($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
	}

	public function destroy() {
		session_destroy();
	}
}