<?php

namespace App\Controllers\Api;

use MyTwitter\Controllers\Controller;

class AppController extends Controller {
	
	public function setStatusCode( $code )
	{
		$this->view->setStatusCode($code);
	}

}