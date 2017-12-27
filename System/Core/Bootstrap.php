<?php
session_name("Session_Unifun_Promo");
session_save_path("SessionData");
ini_set('session.gc_maxlifetime', 3 * 60 * 60);
//ini_set('session.gc_maxlifetime', 30);
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 1);
session_set_cookie_params(3 * 60 * 60);
//session_set_cookie_params(30 );
session_start();

date_default_timezone_set('Europe/Minsk');

require_once('AutoLoader.php');
require_once('Config.php');

spl_autoload_register(array('AutoLoader', 'Load'));

/**
 * Load system and user configuration options
 */
$objConfig = new System\Core\Config();
$AppConfig = $objConfig->GetConfig();

$langConfig = new \Language\Language();


/*
 *  Load the global functions
 */
require_once('Common.php');


$uri = $_SERVER['REQUEST_URI'];


$directory = str_replace('/index.php', "", substr($uri, 0, strpos($_SERVER['PHP_SELF'], '/index.php')));

$uri = str_replace($directory, "", $uri);

$uri = str_replace('index.php', "", $uri);

$uri = strpos($uri, '?') ? substr($uri, 0, strpos($uri, '?')) : $uri;

if ($uri != "/") {
    if (strpos($uri, $AppConfig['AdminFolder']) != false) {
        $uri = str_replace('' . $AppConfig['AdminFolder'], "", $uri);
        $AppConfig['isAdmin'] = true;
        define('PANEL', $AppConfig['AdminFolder']);
    }
}

if (!defined('PANEL'))
    define('PANEL', $AppConfig['WebInterfaceFolder']);


define("MODULE_FOLDER", BASE_PATH . DS . APP_FOLDER . DS . PANEL.DS);
define("SHARED_VIEW", BASE_PATH . DS . APP_PATH . DS . "Shared" . DS . "Views" . DS);


/**
 * 1. Load shortcuts to be able to access Some classes without using there
 * full namespaces.
 * 2. load Application classes ( they are considered global namespace )
 * To be accessible in all the Application
 */
//Autoloader::loadGlobal();


/**
 * Get a router instance and route giving the PHP_INFO if
 * is set then call the launch method to get an instance of
 * the Controller and call the specified method with the args
 */
$router = new System\Core\Router($AppConfig);


if ($uri != "/") {
    // route depending on the path
    $router->pathRoute($uri);
} else {
    // Route to defualt route in the Config
    if ($AppConfig['isAdmin'])
        $router->adminDefaultRoute();
    else
        $router->interfaceDefaultRoute();
}

$router->launch();

// If view instance is null that means the user
// didn't specify any view
/*if(!is_null($view = system\mvc\Views::instance()))
$view->launch();*/