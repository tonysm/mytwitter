<?php

namespace App\View\Helpers;

class Hashtag {
	/**
	 * wraps all hashtags with a ling to /messages/find/hash:example
	 * @param string @text
	 * @return string
	 */
	public function replaceHashtags( $text ){
		$pattern = "/#([a-zA-Z0-9]*)/";
		$replace = '<a href="/messages/find/hash:$1">#$1</a>';
		return preg_replace($pattern, $replace, $text);
	}
}