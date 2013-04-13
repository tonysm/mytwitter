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
		$this->Auth = $this->loadComponent("Auth");
		$this->Session = $this->loadComponent("Session");
		$this->set("Session", $this->Session);
	}
}