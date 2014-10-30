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
			$this->Session->writeMessage("Você precisa estar logado", "error");
			return false;
		}

		return true;
	}
}