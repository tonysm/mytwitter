<?php

namespace MyTwitter\Controllers\Components;

class Session {
	/**
	 * @var array
	 */
	protected $store;
	/**
	 * constructor receives an array to store its data
	 * @param array &$store
	 * @return void
	 */
	public function __construct(array &$store)
	{
		$this->store =& $store;
	}
	/**
	 * get the logged in user
	 * @return array
	 */
	public function getUser()
	{
		return isset($this->store['user']) ? $this->store['user'] : array();
	}
	/**
	 * writes in the store
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public function write($key, $value) {
		// authentication key
		if($key != 'user') {
			$this->store[$key] = $value;
		}
	}
	/**
	 * reads store's stored data
	 * @param string $key
	 * @return mixed
	 */
	public function read($key) {
		return isset($this->store[$key]) ? $this->store[$key] : '';
	}
	/**
	 * destroys the store
	 * @return void
	 */
	public function destroy() {
		store_destroy();
	}
	/**
	 * checks if the user is authenticaded
	 * @return boolean
	 */
	public function isAuthenticated()
	{
		return isset($this->store['user']);
	}
	/**
	 * authenticates the user, storing everything in store
	 * @param array $user user data array
	 * @return void
	 */
	public function authenticate(array $user)
	{
		$this->store['user'] = $user;
	}

	public function logout()
	{
		unset($this->store['user']);
	}
}