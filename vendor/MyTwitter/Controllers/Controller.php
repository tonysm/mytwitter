<?php

namespace MyTwitter\Controllers;

use MyTwitter\Core\Request,
	MyTwitter\View\View;
/**
 * base controller class
 */
class Controller
{
	/**
	 * @var MyTwitter\Core\Request
	 */
	protected $request;
	protected $view;

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
	/**
	 * sets the view class
	 * @param MyTwitter\View\View $View
	 * @return void
	 */
	public function setViewClass(View $View) {
		$this->view = $View;
	}
	/**
	 * renders the view
	 * @param string the view
	 * @return void
	 */
	protected function render( $view ) {
		$this->view->render(strtolower($view));
	}
	/**
	 * sets the view vars
	 * @param string $name the variable name
	 * @param string $value the content of the variable
	 * @return void
	 */
	protected function set($name, $value) {
		$this->view->set($name, $value);
	}

}