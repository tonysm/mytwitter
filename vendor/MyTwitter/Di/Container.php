<?php

namespace MyTwitter\Di;

class Container {
	public static function getDb() {
		$dbdata = require APP_DIR . "database.php";
		extract($dbdata);
		if($driver == 'mysql') {
			return new \PDO("mysql:host={$host};dbname={$dbname}", $user, $pass);
		}

		die('Database driver not implemented');
	}

	public static function getModel($modelName)
	{
		$modelName = ucfirst(strtolower($modelName));
		$model = "App\\Models\\{$modelName}";
		return new $model( self::getDb() );
	}

	public static function getComponent( $component )
	{
		$component = ucfirst(strtolower($component));
		$componentFile = "App\\Controllers\\Components\\{$component}";

		switch($component) {
			case "Auth":
			case "Session":
				return new $componentFile( $_SESSION );
			break;
		}
	}
}