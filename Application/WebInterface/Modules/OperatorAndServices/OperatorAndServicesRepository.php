<?php

namespace WebInterface\Modules\BroadcastingCalendar;

use Infrastructure\MessageCriterionType;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use WebInterface\Models\BroadcastingCalendar;

class OperatorAndServicesRepository extends Repo{

    private $table;

    function __construct()
    {
        $this->table = "broadcasting";

        parent::__construct($this->table, "WebInterface\\Models\\Broadcasting");
        $this->currentDate = $this->GetCurrentDate();

    }



 function GetAllBroadcastingData(){

     $sql = "SELECT id AS broadcastingid,`datefrom`,`text`,promotion,quantity,dateadded  FROM `broadcasting`";
     $sqlQuery = $this->dbConnection->prepare($sql);
     $sqlQuery->execute();
     return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

 }


    function GetAllBrocastingPromotion(AjaxGrid $ajaxGrid,$date)
    {

        $sqlQuery = $this->dbConnection->query("SELECT * FROM broadcasting WHERE datefrom = '$date'");

        $data =  $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->dbConnection->query("SELECT COUNT(*) FROM broadcasting WHERE datefrom = '$date'");

        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];

        $list['Data'] = $data;

        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }


    public function GetAllCountries()
    {
        $sql = "SELECT id,name FROM country ORDER BY name ASC";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function GetAllOperators()
    {
        $sql = "SELECT id,name FROM operator ORDER BY name ASC";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function GetAllOperatorsServices()
    {
        $sql = "SELECT id,name FROM services ORDER BY name ASC";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function GetAllPromotion()
    {
        $sql = "SELECT id,name FROM promotion ORDER BY name ASC";

        $statement = $this->GetDbConnection()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function Save(BroadcastingCalendar $broadcastingCalendar)
    {

        try {

            $this->Insert($broadcastingCalendar, array("id"),'broadcasting');

            return true;
        } catch (\Exception $e) {

        echo     $e->getMessage();
            return false;
        }

    }

    public function Update(Blacklist $blacklist)
    {
        try {

            $this->UpdateTable($blacklist, array("id", "datetime_created", "created_by"), "id");

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }



}