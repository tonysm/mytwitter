<?php

namespace MyTwitter\Controllers;

use MyTwitter\Core\Request;
/**
 * base controller class
 */
class Controller
{
	/**
	 * @var MyTwitter\Core\Request
	 */
	private $request;

	/**
	 * return a instance of the requested controller
	 * @static
	 * @param string the controller name
	 * @return MyTwitter\Controllers\Controller
	 */ 
	public static function getController( $controllerName ) {
		$controller = "App\\Controllers\\{$controllerName}";
		return new $controller();
	}
	/**
	 * sets the request inside the controller
	 * @return void
	 */
	public function setRequest(Request $req) {
		$this->request = $req;
	}
}