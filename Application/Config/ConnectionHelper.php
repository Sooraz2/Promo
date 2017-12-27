<?php

namespace Application\Config;

class ConnectionHelper extends DbConfig
{
    function __construct()
    {
        parent::__construct();
    }

    function dbConnect()
    {

        $db = new \PDO("mysql:host={$this->databaseConnection->ServerName};dbname={$this->databaseConnection->DatabaseName}"
            , $this->databaseConnection->Username, $this->databaseConnection->Password, array(
                \PDO::MYSQL_ATTR_LOCAL_INFILE => true));


        $db->exec("SET CHARACTER SET utf8");
        $db->exec("SET NAMES utf8");

        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $db->exec("USE {$this->databaseConnection->DatabaseName};");



        return $db;
    }


/*    function dbConnectMsSql(){

        $connectionInfo = array( "Database"=>$this->databaseConnection2->DatabaseName, "UID"=> $this->databaseConnection2->Username, "PWD"=>$this->databaseConnection2->Password);
        $db = sqlsrv_connect( $this->databaseConnection2->ServerName, $connectionInfo);

        // $db = mssql_connect($this->databaseConnection2->ServerName, $this->databaseConnection2->Username, $this->databaseConnection2->Password);

        if (!$db) {
            die('Could not connect: ' . mssql_get_last_message());
        }
        //mssql_select_db($this->databaseConnection2->DatabaseName, $db) or die('Could not select database.');

        return $db;

    }


    function dbConnectBPlusMsSql(){

        $connectionInfo = array( "Database"=>$this->databaseConnection3->DatabaseName, "CharacterSet" => "UTF-8", "UID"=> $this->databaseConnection3->Username, "PWD"=>$this->databaseConnection3->Password);
        $db = sqlsrv_connect( $this->databaseConnection3->ServerName, $connectionInfo);

        // $db = mssql_connect($this->databaseConnection2->ServerName, $this->databaseConnection2->Username, $this->databaseConnection2->Password);

        if (!$db) {
            die('Could not connect: ' . mssql_get_last_message());
        }
        //mssql_select_db($this->databaseConnection2->DatabaseName, $db) or die('Could not select database.');

        return $db;

    }*/

    function dbConnectMsSql(){

       // $connectionInfo = array( "Database"=>$this->databaseConnection2->DatabaseName, "UID"=> $this->databaseConnection2->Username, "PWD"=>$this->databaseConnection2->Password);
      //  $db = sqlsrv_connect( $this->databaseConnection2->ServerName, $connectionInfo);

         $db = mssql_connect($this->databaseConnection2->ServerName, $this->databaseConnection2->Username, $this->databaseConnection2->Password);

        if (!$db) {
            die('Could not connect: ' . mssql_get_last_message());
        }
        mssql_select_db($this->databaseConnection2->DatabaseName, $db) or die('Could not select database.');

        return $db;

    }


    function dbConnectBPlusMsSql(){

        //$connectionInfo = array( "Database"=>$this->databaseConnection3->DatabaseName, "CharacterSet" => "UTF-8", "UID"=> $this->databaseConnection3->Username, "PWD"=>$this->databaseConnection3->Password);
        //$db = sqlsrv_connect( $this->databaseConnection3->ServerName, $connectionInfo);
        ini_set('mssql.charset', 'UTF-8');
         $db = mssql_connect($this->databaseConnection3->ServerName, $this->databaseConnection3->Username, $this->databaseConnection3->Password);

        if (!$db) {
            die('Could not connect: ' . mssql_get_last_message());
        }
        mssql_select_db($this->databaseConnection3->DatabaseName, $db) or die('Could not select database.');

        return $db;

    }


}