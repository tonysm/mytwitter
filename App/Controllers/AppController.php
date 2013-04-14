<?php

namespace App\Controllers;

use MyTwitter\Controllers\Controller,
	MyTwitter\Core\Request;

class AppController extends Controller
{
	protected $Auth;
	protected $Session;

	public function __construct(Request $request) {
		parent::__construct( $request );
		$this->Session = $this->loadComponent("Session");
		$this->set("Session", $this->Session);
	}

	public function isAllowed()
	{
		if (!$this->Session->isAuthenticated()) {
			$this->Session->write("message", "Você precisa estar logado");
			$this->Session->write("message-class", "error");
			return false;
		}

		return true;
	}
}