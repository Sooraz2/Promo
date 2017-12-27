<?php

namespace System\Repositories;

use Infrastructure\CookieVariable;
use Infrastructure\LanguageType;
use Application\Config\ConnectionHelper;
use Language\English\English;
use Language\French\French;
use Language\Language;
use Language\Russian\Russian;
use Repositories\MsSqliAdapter;
use WebInterface\Models;
use WebInterface\Models\LoginUserLog;
use Infrastructure\SessionVariables;

class Repo
{
    private static $dbConnection;
  //  private  static  $dbConnectionBalancePlus;
    private static $dbConnectionMssql;
    private static $dbConnectBPlusMsSql;

    private $table;

    private $modelClass;


    function __get($property){


        if($property == "dbConnection"){
            return self::$dbConnection;
        }
        else if($property == "dbConnectionBalancePlus") {

             return self::$dbConnectionBalancePlus;

        }
        else if($property == "dbConnectionMssql") {

            self::$dbConnectionMssql = $this->connectionHelper->dbConnectMsSql();

            return self::$dbConnectionMssql;

        }
        else if($property == "dbConnectBPlusMsSql") {

            self::$dbConnectBPlusMsSql = $this->connectionHelper->dbConnectBPlusMsSql();

            return self::$dbConnectBPlusMsSql;

        }
    }

    function  __construct($table, $modelClass)
    {

        $this->connectionHelper = new ConnectionHelper();
        if(self::$dbConnection == null){

            self::$dbConnection = $this->connectionHelper->dbConnect();
        }

       // $this->dbAdapter = new MsSqliAdapter();

      //  $this->UserLogModel = new LoginUserLog();

        $this->table = $table;

        $this->modelClass = $modelClass;

    }

    protected function GetDbConnection()
    {
       // if(self::$dbConnection == null) {

           // $this->connectionHelper = new ConnectionHelper();
           // self::$dbConnection = $this->connectionHelper->dbConnect();
      //  }
        return self::$dbConnection;
    }

    protected function Insert($model, $removeFields = array(), $table = null)
    {
        $modelArray = (array)$model;

        if ($table == null)
            $table = $this->table;

        foreach ($removeFields as $removeField) {
            unset($modelArray[$removeField]);
        }

        $insertSql = "INSERT INTO `{$table}` (";

        $keys = array_keys($modelArray);

        $insertSql .= '`' . implode('`,`', $keys) . '`' . ") ";

        $insertSql .= "VALUES(";

        $insertSql .= ":" . implode(',:', $keys) . ")";

        $sqlQuery = $this->GetDbConnection()->prepare($insertSql);

        foreach ($modelArray as $key => $value) {
            $sqlQuery->bindValue(":" . $key, $value);
        }
        $sqlQuery->execute();

        return $this->GetDbConnection()->lastInsertId();
    }

    protected function UpdateTable($model, $removeFields, $id = null, $updateFrom = null, $updateFromValue = null)
    {
        $modelArray = (array)$model;

        foreach ($removeFields as $removeField) {
            unset($modelArray[$removeField]);
        }

        $updateSql = "UPDATE `{$this->table}` SET ";

        $keys = array_keys($modelArray);

        foreach ($keys as $key) {
            $updateSql .= "`$key`=:$key,";
        }

        $updateSql = rtrim($updateSql, ',');

        if ($updateFrom == null) {
            if ($id == null)
                $updateSql .= " WHERE ID=:ID";
            else
                $updateSql .= " WHERE $id=:$id";
        } else
            $updateSql .= " WHERE $updateFrom=:$updateFrom";

        $sqlQuery = $this->GetDbConnection()->prepare($updateSql);

        if ($updateFrom == null) {
            if ($id == null)
                $sqlQuery->bindValue(":ID", $model->ID);
            else
                $sqlQuery->bindValue(":$id", $model->$id);
        } else
            $sqlQuery->bindValue(":$updateFrom", $updateFromValue);

        foreach ($modelArray as $key => $value) {
            $sqlQuery->bindValue(":" . $key, $value);
        }

        $sqlQuery->execute();
    }


    public function GetCurrentDate()
    {

        $sqlQuery = $this->GetDbConnection()->query("SELECT CURRENT_DATE()");

        $date = $sqlQuery->fetchColumn();

        return $date;
    }

    public function GetCurrentDateTime()
    {
        $sqlQuery = $this->GetDbConnection()->query("SELECT CURRENT_TIMESTAMP");

        $datetime = $sqlQuery->fetchColumn();

        return $datetime;
    }

    public function Delete($id, $idFieldName = null)
    {
        try {
            if ($idFieldName == null) {
                $sqlQuery = $this->GetDbConnection()->prepare("DELETE FROM {$this->table} WHERE ID=:ID");
                $sqlQuery->bindValue("ID", $id);
            } else {
                $sqlQuery = $this->GetDbConnection()->prepare("DELETE FROM {$this->table} WHERE $idFieldName=:$idFieldName");
                $sqlQuery->bindValue("$idFieldName", $id);
            }

            $sqlQuery->execute();

            return true;
        } catch (\Exception $e) {

            return false;
        }
    }

    public function GetById($id, $idFieldName = null)
    {
        if ($idFieldName == null) {
            $sqlQuery = $this->GetDbConnection()->prepare("SELECT * FROM `{$this->table}` WHERE ID=:ID");
            $sqlQuery->bindParam(':ID', $id);
        } else {
            $sqlQuery = $this->GetDbConnection()->prepare("SELECT * FROM `{$this->table}` WHERE $idFieldName=:$idFieldName");
            $sqlQuery->bindParam(":$idFieldName", $id);
        }
        $sqlQuery->execute();

        $model = new $this->modelClass();

        while ($row = $sqlQuery->fetch(\PDO::FETCH_ASSOC)) {
            $model->MapParameters($row);
        }

        return $model;
    }

    public function GetAllByViewModelWithOutJoin($viewModelClass, $whereConditions = array())
    {
        $viewModel = new $viewModelClass();

        $viewModelArray = (array)$viewModel;

        $keys = array_keys($viewModelArray);

        $sql = "SELECT ";

        foreach ($keys as $key) {
            $sql .= " [$key],";
        }

        $sql = rtrim($sql, ',');

        $sql .= " FROM `{$this->table}`";

        if (count($whereConditions) != 0) {
            $sql .= " WHERE 1 ";

            foreach ($whereConditions as $whereCondition) {
                $sql .= " AND $whereCondition`Field`=$whereCondition[Match]";
            }
        }

        $list = array();

        $sqlQuery = $this->GetDbConnection()->query($sql);

        foreach ($sqlQuery->fetchAll(\PDO::FETCH_ASSOC) as $row) {
            $viewModel = new $viewModelClass();
            $viewModel->MapParameters($row);

            array_push($list, $viewModel);
        }

        return $list;
    }

    public function GetAll($orderBY = false, $orderType = false, $specificColumn = null)
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
        $sqlQuery = $this->GetDbConnection()->query($sql);

        $list = array();

        foreach ($sqlQuery->fetchAll(\PDO::FETCH_ASSOC) as $row) {

            $model = new $this->modelClass();

            $model->MapParameters($row);

            array_push($list, $model);
        }
        return $list;
    }

    public function UpdateLog($action, $replaceArray = null)
    {
        $this->UserLogModel->UserID = $_SESSION[SessionVariables::$UserID];
        $this->UserLogModel->DateTime = $this->GetCurrentDateTime();

        $varKey = returnVarKey("\Language\English\Logs", $action);

        if (isset($_COOKIE[CookieVariable::$BalancePlusLanguage]) && $_COOKIE[CookieVariable::$BalancePlusLanguage] == "Russian") {
            $this->UserLogModel->ActionFR = replaceString(\Language\Russian\Logs::$$varKey, $replaceArray);

            $keys = array_keys($replaceArray);
            $English = new English();
            $Russian = new Russian();
            foreach ($keys as $tempkey) {
                $key = array_search($replaceArray[$tempkey], (array)$Russian);
                $temp = $key ? $English->$key : false;
                $replaceArray[$tempkey] = $temp ? $temp : $replaceArray[$tempkey];
            }

            $this->UserLogModel->Action = replaceString($action, $replaceArray);


        } else {
            $this->UserLogModel->Action = replaceString($action, $replaceArray);

            $keys = array_keys($replaceArray);
            $English = new English();
            $Russian = new Russian();
            foreach ($keys as $tempkey) {
                $key = array_search($replaceArray[$tempkey], (array)$English);
                $temp = $key ? $Russian->$key : false;
                $replaceArray[$tempkey] = $temp ? $temp : $replaceArray[$tempkey];
            }

            $this->UserLogModel->ActionFR = replaceString(\Language\Russian\Logs::$$varKey, $replaceArray);

        }


        $this->Insert($this->UserLogModel, array("ID"));
    }

    /* where query
     * return @array
     * */
    public function Where($array, $orderBY = false, $orderType = false)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE 1 ";
        $concatSql = "";
        foreach ($array as $key => $value) {
            $key = (trim($key));
            $value = (trim($value));
            $concatSql .= " AND `$key` = :$key";
        }
        $sql .= $concatSql;
        if ($orderBY) {
            $sql .= " ORDER BY {$orderBY} {$orderType} ";
        }
        $sqlQuery = $this->GetDbConnection()->prepare($sql);
        $newArr = array();
        foreach ($array as $key => $value) {
            $newArr[":" . $key] = $value;
        }
        $sqlQuery->execute($newArr);
        $result = $sqlQuery->fetchAll();

        return $result;
    }

    public function WhereReturnModel($array, $orderBY = false, $orderType = false)
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE 1 ";
        $concatSql = "";
        foreach ($array as $key => $value) {
            $key = (trim($key));
            $value = (trim($value));
            $concatSql .= " AND `$key` = :$key";
        }
        $sql .= $concatSql;
        if ($orderBY) {
            $sql .= " ORDER BY {$orderBY} {$orderType} ";
        }
        $sqlQuery = $this->GetDbConnection()->prepare($sql);
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

    /* where query
    * return @object
    * */
    public function Count($array = null)
    {
        $sql = "SELECT COUNT(*) as Count FROM `{$this->table}` WHERE 1 ";
        if (is_array($array)) {
            $concatSql = "";
            foreach ($array as $key => $value) {
                $key = trim($key);
                $value = trim($value);
                $concatSql .= " AND `$key` = :$key";
            }
            $sql .= $concatSql;
            $sqlQuery = $this->GetDbConnection()->prepare($sql);
            $newArr = array();
            foreach ($array as $key => $value) {
                $newArr[":" . $key] = $value;
            }
            $sqlQuery->execute($newArr);
        } else {
            $sqlQuery = $this->GetDbConnection()->prepare($sql);
            $sqlQuery->execute();
        }
        $result = $sqlQuery->fetchObject();
        return $result;
    }

    function  BuildSeparationStringForCsvUpload($separationTypeList = array(), $otherVal = null)
    {
        $separationString = "";
        $otherSeparationString = "";

        if (empty($separationTypeList))
            $separationString = "\\r\\n";

        if (in_array("space", $separationTypeList)) {
            $separationString .= "\\x20";
        }

        if (in_array("tab", $separationTypeList)) {
            $separationString .= "\\t";
        }

        if (in_array("comma", $separationTypeList)) {
            $separationString .= ",";
        }

        if (in_array("semicolon", $separationTypeList)) {
            $separationString .= ";";
        }

        if (in_array("other", $separationTypeList)) {
            $otherSeparationString = ($otherVal != null && !empty($otherVal)) ? $otherVal : "\\r";
        }

        if ($separationString == '') {
            $separationString = '[,]';
        } else {
            $separationString = "[" . $separationString . "]";

            if (in_array("other", $separationTypeList)) {
                $separationString .= " | (" . $otherSeparationString . ")";
            }
        }


        return $separationString;
    }

    public function replaceSeperator($filename, $separator)
    {
        $handle = fopen($filename, "r");
        $string = '';
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.
                $string .= preg_replace('/' . $separator . '/', ',', $line).PHP_EOL;
            }
            fclose($handle);
            $string = rtrim($string);
            file_put_contents($filename, $string);
        } else {
            // error opening the file.
        }
    }

    public function GetDateOrTime($datetime, $type){
        if($type == "date")
            return substr($datetime, 0, 10);
        else
            return substr($datetime, 11, 8);
    }

}