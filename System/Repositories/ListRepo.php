<?php
/**
 * Created by PhpStorm.
 * User: Anup
 * Date: 12/22/2015
 * Time: 10:23 AM
 */

namespace System\Repositories;

use Application\Config\ConnectionHelper;

abstract class ListRepo
{

    protected $dbConnection;

    private $table;

    private $modelClass;

    function  __construct()
    {
        $connectionHelper = new ConnectionHelper();

        $this->dbConnection = $connectionHelper->dbConnect();
    }

    protected function SetModel($table, $modelClass)
    {
        $this->table = $table;

        $this->modelClass = $modelClass;
    }

    protected function GetAll($orderBY = false, $orderType = false, $specificColumn = null)
    {
        $sql = "SELECT ";

        if ($specificColumn) {

            $specificColumn = implode(", ", $specificColumn);

            $sql .= "{$specificColumn} FROM `{$this->table}`";

        } else {

            $sql .= " * FROM `{$this->table}`";

        }

        if ($orderBY) {
            $sql .= "ORDER BY `{$orderBY}` {$orderType}";
        }

        $sqlQuery = $this->dbConnection->query($sql);

        $list = array();

        foreach ($sqlQuery->fetchAll(\PDO::FETCH_ASSOC) as $row) {

            $model = new $this->modelClass();

            $model->MapParameters($row);

            array_push($list, $model);
        }
        return $list;
    }

    protected function Where($array, $orderBY = false, $orderType = false)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE 1 ";

        foreach ($array as $key => $value) {

            $key = (trim($key));
            $sql .= " AND `$key` = :$key";
        }

        if ($orderBY) {
            $sql .= " ORDER BY {$orderBY} {$orderType} ";
        }

        $sqlQuery = $this->dbConnection->prepare($sql);

        $newArr = array();

        foreach ($array as $key => $value) {
            $newArr[":" . $key] = $value;
        }


        $sqlQuery->execute($newArr);

        $list = array();

        foreach ($sqlQuery->fetchAll(\PDO::FETCH_ASSOC) as $row) {

            $model = new $this->modelClass();

            $model->MapParameters($row);

            array_push($list, $model);
        }

        return $list;
    }

} 