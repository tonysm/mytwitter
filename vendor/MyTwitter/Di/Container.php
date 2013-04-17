<?php

namespace MyTwitter\Di;

class Container {
	public static function getDb( $connection = "default" ) {
		$dbdata = require APP_DIR . "database.php";
		extract($dbdata[ $connection ]);
		if(isset($driver) && $driver == 'mysql') {
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

	public static function getModelToTest($modelName)
	{
		$modelName = ucfirst(strtolower($modelName));
		$model = "App\\Models\\{$modelName}";
		return new $model( self::getDb( "test" ) );
	}

	public static function getComponent( $component )
	{
		$component = ucfirst(strtolower($component));

		if (self::componentExistsInApp( $component )) {
			$componentFile = "App\\Controllers\\Components\\{$component}";
		} else {
			$componentFile = "MyTwitter\\Controllers\\Components\\{$component}";
		}

		switch($component) {
			case "Session":
				return new $componentFile( $_SESSION );
			break;
		}
	}
	/**
	 * checks if a components exists in App
	 * @param string $component
	 * @return boolean
	 */
	public static function componentExistsInApp( $component )
	{
		return file_exists(CONTROLLERS_DIR . "Components" . DS . $component . ".php");
	}
}