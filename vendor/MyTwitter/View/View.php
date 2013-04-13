<?php

namespace MyTwitter\View;

interface View {
	public function set($varName, $value);
	public function render( $viewFile );
	public function serialize(array $variablesToSerialize);
}