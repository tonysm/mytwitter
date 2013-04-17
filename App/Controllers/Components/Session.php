<?php

namespace App\Controllers\Components;

class Session {
	/**
	 * @var array
	 */
	protected $session;

	public function __construct(array &$session)
	{
		$this->session =& $session;
	}
	/**
	 * get the logged in user
	 * @return array
	 */
	public function getUser()
	{
		return isset($this->session['user']) ? $this->session['user'] : array();
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
			$this->session[$key] = $value;
		}
	}
	/**
	 * reads session's stored data
	 * @param string $key
	 * @return mixed
	 */
	public function read($key) {
		return isset($this->session[$key]) ? $this->session[$key] : '';
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
		return isset($this->session['user']);
	}
	/**
	 * authenticates the user, storing everything in session
	 * @param array $user user data array
	 * @return void
	 */
	public function authenticate(array $user)
	{
		$this->session['user'] = $user;
	}

	public function logout()
	{
		unset($this->session['user']);
	}
}