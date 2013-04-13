<?php
namespace MyTwitter\Core;

use MyTwitter\View\HtmlView;

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
	private $extras;
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
	 * returns the view class
	 * @return MyTwitter\View\View
	 */
	public function getViewClass()
	{
		return $this->viewClass;
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
		$http_method = $this->getHttpMethod();
		$this->action = (isset($this->explodedUri[0]) && !empty($this->explodedUri[0])) 
			? strtolower(array_shift($this->explodedUri)) 
			: 'index';
		$this->action = $http_method . '_' . $this->action;
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
	 * @return MyTwitter\View\View
	 */
	private function _setViewClass() {
		$this->viewClass = new HtmlView();
	}
	/**
	 * returns the http verb of the request
	 * @return string
	 */
	private function getHttpMethod()
	{
		return strtolower($_SERVER['REQUEST_METHOD']);
	}
	/**
	 * get the data sent by a form
	 * @param string $indexName the name of the data
	 * @return array
	 */
	public function getData( $indexName ) {
		$http_verb = $this->getHttpMethod();

		switch($http_verb) {
			case 'post':
				return isset($_POST[$indexName]) ? $_POST[$indexName] : array();
			break;
			case 'get':
				return isset($this->extras[$indexName]) ? $this->extras[$indexName] : array();
			break;
			default:
				return array();
			break;
		}
	}

}