<?php

namespace Application\Config;


use Shared\Model\DatabaseConnection;

class DbConfig
{
    protected $databaseConnection;

    function __construct()
    {
        $this->databaseConnection = new DatabaseConnection();
        $this->databaseConnection->ServerName = 'localhost';
        $this->databaseConnection->Username = 'root';
        $this->databaseConnection->Password = '';
        $this->databaseConnection->DatabaseName = 'unifun_promo_interface';



        $this->databaseConnection2 = new DatabaseConnection();
       // $this->databaseConnection2->ServerName = '10.8.1.81';
        //$this->databaseConnection2->ServerName = 'gw1.unifun.com';
        $this->databaseConnection2->ServerName = '192.168.1.95';
        $this->databaseConnection2->Username = 'sa';
        $this->databaseConnection2->Password = 'i7D130MQQe';
        $this->databaseConnection2->DatabaseName = 'VCHReports';



        $this->databaseConnection3 = new DatabaseConnection();
      //  $this->databaseConnection3->ServerName = '10.8.1.81';
        //$this->databaseConnection3->ServerName = 'gw1.unifun.com';
        $this->databaseConnection3->ServerName = '192.168.1.95';
        $this->databaseConnection3->Username = 'sa';
        $this->databaseConnection3->Password = 'i7D130MQQe';
        $this->databaseConnection3->DatabaseName = 'BalancePlusCMS';
    }
}