<?php
class ConfigLoader {

	protected static $_configs = array();

	//Config laden die in "App/Config" staat en een variabele uit halen.
    // Daar wordt bestand met als naam
    //$config_alias en dan .php gebruikt. Andere configs kunnen ook gebruikt worden
	public static function get($key, $config_alias = 'main_config') {
	    // Als config nog niet in array geladen is.
		if(!isset($_configs[$config_alias])) {
		    //Wordt het geladen
			$_configs[$config_alias] = require CONFIGS . $config_alias . '.php';
		}
		if(isset($_configs[$config_alias][$key])) {
			return $_configs[$config_alias][$key];
		}
		//geen sleutel
		return null;
	}
	
	
}