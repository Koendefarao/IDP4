<?php
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
define('ROOT', dirname(__FILE__));
define('FRW_FILES', ROOT . DS . 'framework' . DS);
define('FRW_COMPONENTS', FRW_FILES . DS . 'Components' . DS);
define('FRW_LIBS', FRW_FILES . DS . 'Libraries' . DS);
define('APP', ROOT . DS . 'app' . DS);
define('APP_TABLES', APP . 'Models' . DS . 'Tables' . DS);
define('APP_ENTITIES', APP . 'Models' . DS . 'Entities' . DS);
define('APP_CONTROLLERS', APP . 'Controllers' . DS);
define('APP_TEMPLATES', APP . 'Templates' . DS);
define('APP_LAYOUTS', APP . 'Templates' . DS . 'Layout' . DS);
define('APP_COMPONENTS', APP . DS . 'Components' . DS);
define('CONFIGS', APP . DS . 'Config' . DS);
// Variabelen definieren  van alle paden die er zijn

include_once(FRW_FILES . 'Loaders' . DS . 'ConfigLoader.php');
// COnfiguratie laden

$GLOBALS['action'] = 'index';
function startup()
{
    //Pad die op url wordt bezocht www.test.com/bla/bla?foo=bar
    //                                         ↑ hierna komd de pad
    $path = ltrim($_SERVER['REQUEST_URI'], '/');
    //De get params halen dus foo=bar
    $query = explode('?', $path);
    // Alles wat voor get params staat (?foo=bar) wordt gesplitst met /
    // dua array met bla en bla
    $elements = explode('/', $query[0]);
    // Haalt url erui www.test.com
	$base = $_SERVER['HTTP_HOST'];

	// Haalt uit config hoeveel paden het het url moet verschuiven
    // 1 pade verschuin levert dit op: www.test.com/bla
    // 2 paden verschuiven www.test.com/bla/bla
    // Handig voor testen op xampp want localhost/mijnproject
	for($i = 0; $i < ConfigLoader::get('url_offset'); $i++) {
		$base .= '/'.$elements[$i];
	}
	define('BASE', $base);
    for ($i = 0; $i < ConfigLoader::get('url_offset'); $i++) {
        unset($elements[0]);
    }
    // Haal lege paden uit array
	$elements = array_values($elements);
	for($i = count($elements)-1; $i >= 0; $i--) {
		if(empty($elements[$i]) || $elements[$i] == '') {
			unset($elements[$i]);
		}
	}

	// Voeg controller pad toe; als die er niet is gerbuid standard controller uit
    // config
    if (count($elements) == 0) {
        $GLOBALS['controller'] = ConfigLoader::get('default_controller');
        return;
    } else {
        $GLOBALS['controller'] = $elements[0];
    }

    // Als actie in url staat gebruik de actie. anders gebruik index
    if (count($elements) > 1) {
        $GLOBALS['action'] = $elements[1];
    }
}

// Haal controller phpclass uit app/controllers.
// Standaard naamgeving MyController.php $controller is dan my
function getController($controller)
{
    $ctrl_name = ucfirst($controller) . 'Controller';
    include_once(APP_CONTROLLERS . $ctrl_name . '.php');
    return new $ctrl_name();
}

//Start op
startup();
//Voer controller actie uit
$controller_instance = getController($GLOBALS['controller']);
$controller_instance->callAction( $GLOBALS['action']);