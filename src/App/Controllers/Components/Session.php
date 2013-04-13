<?php

namespace App\Controllers\Components;

class Session {

	public function __construct() {
	}
	/**
	 * writes in the session
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function write($key, $value) {
		// authentication key
		if($key != 'user') {
			$_SESSION[$key] = $value;
		}
	}
	/**
	 * reads session's stored data
	 * @param string $key
	 * @return mixed
	 */
	public function read($key) {
		return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
	}
	/**
	 * destroys the session
	 * @return void
	 */
	public function destroy() {
		session_destroy();
	}
	/**
	 * checks if the user is authenticaded
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		return isset($_SESSION['user']);
	}
	/**
	 * authenticates the user, storing everything in session
	 * @param array $user user data array
	 * @return void
	 */
	public function authenticate(array $user)
	{
		$_SESSION['user'] = $user;
	}

	public function logout()
	{
		unset($_SESSION['user']);
	}
}