<?php

namespace MyTwitter\Controllers;

use MyTwitter\Core\Request,
	MyTwitter\View\View,
	MyTwitter\Di\Container;
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

	public function __construct(Request $request) {
		$this->request = $request;
		$this->view = $request->getViewClass();
	}

	/**
	 * return a instance of the requested controller
	 * @static
	 * @param string the controller name
	 * @return MyTwitter\Controllers\Controller
	 */ 
	public static function getController( $controllerName, Request $request ) {
		$controller = "app\\Controllers\\{$controllerName}";
		if(self::controlllerExists( $controllerName )) {
			return new $controller( $request );
		}

		throw new \Exception("Error Processing Request");
	}

	public static function controlllerExists( $controllerName )
	{
		$controllerName = str_replace("\\\\", "/", $controllerName);
		$controllerName = str_replace("\\", "/", $controllerName);
		$file = CONTROLLERS_DIR . $controllerName . ".php";

		return file_exists($file);
	}
	/**
	 * loads a model
	 * @param string $modelName the name of the model
	 * @return MyTwitter\Models\Model
	 */
	public function loadModel( $modelName ) {
		return Container::getModel( $modelName );
	}
	/**
	 * loads a component
	 * @param string $componentName the component name
	 * @return MyTwitter\Controllers\Components\Component
	 */
	public function loadComponent( $componentName ) {
		return Container::getComponent( $componentName );
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
	/**
	 * redirects the user
	 * @param string $route
	 */
	protected function redirect( $route ) {
		header("Location:{$route}");
		exit();
	}

}