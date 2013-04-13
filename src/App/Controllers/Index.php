<?php

namespace App\Controllers;

use MyTwitter\Controllers\Controller;

/**
* Index controller
*/
class Index extends Controller
{
	public function get_index() {
		$this->set('teste', 'lorem ipsum');
		$this->render('index/index');
	}

}