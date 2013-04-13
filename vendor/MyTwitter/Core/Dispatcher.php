<?php

namespace MyTwitter\Core;

use MyTwitter\Controllers\Controller;

/**
 * responsible to initialize the application
 */
class Dispatcher
{
	/**
	 * processes the request, it gets the respective controller and calls it action
	 * @return void
	 */
	public static function process( Request $request) {
		$Controller = Controller::getController( $request->getController() );
		$Controller->setRequest( $request );
		$Controller->setViewClass( $request->getViewClass() );
		$action = $request->getAction();

		if(!method_exists($Controller, $action)) {
			throw new \Exception("Action does not exist {$request->getController()}::{$action}");
		}

		$Controller->$action( $request->getParams() );
	}
}