<?php

namespace App\Controllers;

class Index extends AppController
{
	public function get_index()
    {
		$this->render('index/index');
	}

}