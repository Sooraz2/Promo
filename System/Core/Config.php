<?php
namespace System\Core;

class Config
{
    private $config;
    private $app;
    private $db;

    function __construct()
    {
        $this->config = array();
        $this->app = array();
        $this->db = array();

        $this->initialize();
    }

    /**
     * Load user Configurations
     *
     * @return void
     */
    private function initialize()
    {
        $this->config['AdminFolder'] = "Admin";
        $this->config['WebInterfaceFolder'] = "WebInterface".DS."Modules";
        $this->config['isAdmin'] = false;
        $this->config['InterfaceDefaultRoute'] = array('Controller' => 'Login', 'Action' => 'Index');
        $this->config['AdminDefaultRoute'] = array('Controller' => 'UserManagement', 'Action' => 'Index');
       // $this->config['database'] = require BASE_PATH . '\\Application\\Config\\DbConfig.php';
    }

    function GetConfig()
    {
        return $this->config;
    }

}