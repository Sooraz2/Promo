<?php
namespace System\Core;

class Router
{

    public $controller;

    /**
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $args = array();

    protected $SystemConfig;

    public function __construct($systemConfig)
    {

        $this->SystemConfig = $systemConfig;
    }

    /**
     * Sets the default controller and method defined in the Config
     *
     * @return void
     */
    public function interfaceDefaultRoute()
    {
        $this->controller = $this->SystemConfig['InterfaceDefaultRoute']['Controller'];
        $this->method = $this->SystemConfig['InterfaceDefaultRoute']['Action'];
    }

    public function adminDefaultRoute()
    {
        $this->controller = $this->SystemConfig['AdminDefaultRoute']['Controller'];
        $this->method = $this->SystemConfig['AdminDefaultRoute']['Action'];
    }

    /**
     * Sets the controller and method depending on the path
     *
     * @return void
     */
    public function pathRoute($uri = '')
    {
        // Remove any trailing slashes
        $parts = trim($uri, '/');

        // Explode the url
        $parts = explode('/', $parts);

        // The first part of the url is the controller
        $this->controller = array_shift($parts);


        // The second part is the controller method
        // we check if it's set
        if (isset($parts[0]))
            // Set the method to the second part
            $this->method = array_shift($parts);
        else
            // WebInterface method (index) called if no method specified
            $this->method = 'index';

        // Set the args to the rest of the url parts
        $this->args = $parts;
    }


    /**
     * This method creates new controller from the url and return
     * whatever the method specified by the url returns
     *
     * @return void
     */
    public function launch()
    {

        // Fix Controller name and append the '_Controller'
        $class = $this->controller;


        //var_dump($class);exit;
        // Check if predefined Controller class exists


        if (preg_match('/WebInterface/', PANEL) && file_exists(APP_PATH . DS . PANEL . DS . $class . DS . $class . 'Controller.php') && class_exists(str_replace('/', '\\', PANEL) . "\\" . "$class\\" . $class . 'Controller')) {
            $controller = str_replace('/', '\\', PANEL) . "\\" . "$class\\" . $class . 'Controller';

            $controller = new $controller;
        } else if (preg_match('/Admin/', PANEL) && file_exists(APP_PATH . DS . PANEL . DS . "Controllers" . DS . $class . 'Controller.php') && class_exists(PANEL . "\\" . "Controllers\\" . $class . 'Controller')) {
            $controller = PANEL . "\\" . "Controllers\\" . $class . 'Controller';
            $controller = new $controller;
        }//search shared controllers
        elseif (file_exists(APP_PATH . DS . "Shared" . DS . "Controllers" . DS . $class . 'Controller.php') && class_exists("Shared" . "\\" . "Controllers\\" . $class . 'Controller')) {
            $controller = "Shared" . "\\" . "Controllers\\" . $class . 'Controller';
            $controller = new $controller;
        } // Check if predefined Model class exists and execute through CrudController
        else {
            // Controller doesn't exist
            // WebInterface error controller is created instead
            /*$controller = new \Error_Controller;

            // Call the index method
            return $controller->index();*/

            var_dump("$class controller does not exists");
        }


        /*if(!$controller->restful)
            // If no restful then the method name is
            // prepended with 'action_' like laravel!*/
        $method = $this->method . "Action";
        /*
        else
        {
            // Restful is set to true so preppend the request name
            // ( POST, GET, PUT, DELETE, HEAD ) to the method
            $method = strtolower($_SERVER['REQUEST_METHOD'])."_" .$this->method;
        }*/
        // Check if the method exists in the controller
        if (method_exists($controller, $method)) {
            // Call the method giving the args array
            return call_user_func_array(array($controller, $method), $this->args);
        } else {
            // Method doesn't exist
            // WebInterface error controller is created instead
            /*$controller = new \Error_Controller;

            // Call the index method
            return $controller->index();*/

            var_dump("$method does not exists in controller $class");
        }
    }
}