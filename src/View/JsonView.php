<?php

namespace MyTwitter\View;

class JsonView implements View {
	protected $vars = array();
	protected $status_code = 200;

	public function set($varName, $value) {
		$this->vars[$varName] = $value;
	}
	public function render( $viewFile ) {
		extract($this->vars);
		header(":", true, $this->status_code);
		header("Content-Type: application/json");
		require VIEWS_DIR . strtolower($viewFile) . ".phtml";
	}

	public function setStatusCode($code)
	{
		$this->status_code = $code;	
	}
}