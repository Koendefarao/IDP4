<?php
include_once FRW_FILES . 'Loaders/ConfigLoader.php';

class TableLoader {
	
	protected static $_mysqli = null;
	
	protected static $_tables = array();

	public static function connectSQL() {
		if(self::$_mysqli == null) {
			self::$_mysqli = new mysqli(
				ConfigLoader::get('db_host'), 
				ConfigLoader::get('db_username'), 
				ConfigLoader::get('db_password'), 
				ConfigLoader::get('db_name')
			);
			
		}
		if (self::$_mysqli->connect_error) {
			return null;
		}
        return self::$_mysqli;
	}

    /**
     * @param $name
     * @return Table
     */
    public static function get($name) {
		TableLoader::connectSQL();
		$name = ucfirst($name) . 'Table';
		if(!isset(self::$_tables[$name])) {
			include_once(APP_TABLES . $name .'.php');
			$table = new $name();
			self::$_tables[$name] = $table;
		}
		return self::$_tables[$name];
	}
	
}