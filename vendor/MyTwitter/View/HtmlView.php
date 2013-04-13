<?php

namespace MyTwitter\View;

class HtmlView implements View {
	private $vars = array();
	
	public function set($varName, $value) {
		$this->vars[$varName] = $value;
	}

	public function render( $viewFile ) {
		$header = VIEWS_DIR . "layouts/header.phtml";
		$footer = VIEWS_DIR . "layouts/footer.phtml";
		$viewPath = VIEWS_DIR . strtolower($viewFile) . ".phtml";
		extract($this->vars);
		require $header;
		require $viewPath;
		require $footer;
	}

	public function serialize(array $variablesToSerialize) {
		// nothing yet
	}
}