<?php
namespace MyTwitter\Core;

use MyTwitter\View\HtmlView,
	MyTwitter\View\JsonView;

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
	private $ext = null;
	private $ajax = false;
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

		// if the uri is something like example.com/user.json
		// it will be executed as a ajax request
		$this->_buildApiAppIfExtensionJson();

		$this->explodedUri = explode('/', $this->uri);
		$this->extras = $GET;
		$this->_setController();
		$this->_setAction();
		$this->_setParams();
		$this->_setViewClass();
	}
	/**
	 * sets the $this->ext to json and $this->ajax to TRUE
	 * if the URI contains .json at the end
	 * @return void
	 */
	private function _buildApiAppIfExtensionJson()
	{
		$ext = "json";
		$pattern = "/^.*\.json$/";
		if (preg_match($pattern, $this->uri)) {
			$this->uri = str_replace(".json", "", $this->uri);
			$this->ext = $ext;
			$this->ajax = true;
		}
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
	 * return a single param
	 * @param string $key
	 * @return mixed
	 */
	public function getParam($key)
	{
		return isset($this->params[$key]) ? $this->params[$key] : '';
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
			
		if ($this->ajax) {
			$this->controller = "Api\\" . $this->controller;
		}
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
		if (!$this->ajax) {
			$this->action = (isset($this->explodedUri[0]) && !empty($this->explodedUri[0])) 
				? strtolower(array_shift($this->explodedUri)) 
				: 'index';
			$this->action = $http_method . '_' . $this->action;
		} else {
			if(isset($this->explodedUri[1]) && !empty($this->explodedUri[1])) {
				$this->action = $http_method . '_' . strtolower($this->explodedUri[1]);
			} else {
				$this->action = $http_method . '_index';
			}
		}
	}
	/**
	 * gets the rest of the URI as params
	 * @return void
	 */
	private function _setParams()
	{
		if (isset($this->explodedUri[0])) {
			if (!$this->ajax) {
				foreach($this->explodedUri as $param) {
					$exp = explode(":", $param);
					$this->params[$exp[0]] = $exp[1];
				}
			} else {
				$this->params['id'] = $this->explodedUri[0];
			}
		}
		unset($this->explodedUri);
	}
	/**
	 * @return MyTwitter\View\View
	 */
	private function _setViewClass() {
		if ($this->ajax) {
			$this->viewClass = new JsonView();
		} else {
			$this->viewClass = new HtmlView();
		}
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