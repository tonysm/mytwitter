<?php
namespace MyTwitter\Core;

/**
 * class responsable to map the request
 */
class Request
{
	private $uri;
	private $explodedUri;
	private $controller;
	private $action;
	private $params;
	/**
	 * @var the class responsable to render the view
	 */
	private $viewClass;

	/**
	 * constructor accepts the $_GET global
	 * @param array $GET the $_GET equivalent
	 * @return void
	 */
	public function __construct( array $GET ) {
		$this->uri = isset($GET['url']) ? $GET['url'] : 'Index/index';
		unset($GET['url']);
		$this->explodedUri = explode('/', $this->uri);
		$this->extras = $GET;
		$this->_setController();
		$this->_setAction();
		$this->_setParams();
		$this->_setViewClass();
	}
	/**
	 * returns the controller responsable for the request
	 * @return MyTwitter\Controllers\Controller
	 */
	public function getController() {
		return $this->controller;
	}

	/**
	 * return the action to be executed, the action must be
	 * prefixed with the HTTP method of the request, ex:
	 * get_index or post_index
	 * 
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}
	/**
	 * return the params (extra params) of the request
	 * 
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * this is a different kind of set, it doesn't receive params
	 * instead, it gets from the request
	 * DEFAULT Index
	 * 
	 * @return void
	 */
	private function _setController()
	{
		$this->controller = (isset($this->explodedUri[0]) && !empty($this->explodedUri[0])) 
			? ucfirst(strtolower(array_shift($this->explodedUri))) 
			: 'Index';
	}

	/**
	 * this is a different kind of set, it doesn't receive params,
	 * instead, it get from the request
	 * ATTENTION: it uses the $_SERVER[REQUEST_METHOD] to prefix the action name
	 * DEFAULT get_index
	 * 
	 * @return void
	 */
	private function _setAction()
	{
		$http_method = strtolower($_SERVER['REQUEST_METHOD']);
		$this->action = (isset($this->explodedUri[0]) && !empty($this->explodedUri[0])) 
			? $http_method . '_' . strtolower(array_shift($this->explodedUri)) 
			: 'get_index';
	}
	/**
	 * gets the rest of the URI as params
	 * @return void
	 */
	private function _setParams()
	{
		$this->params = (isset($this->explodedUri[0]) && !empty($this->explodedUri[0]))
			? $this->explodedUri
			: array();
		unset($this->explodedUri);
	}
	/**
	 * TODO create a View interface
	 * TODO create a HtmlView Implementation to render common html views
	 * @return void
	 */
	private function _setViewClass() {
		$this->viewClass = new \stdClass();
	}

}