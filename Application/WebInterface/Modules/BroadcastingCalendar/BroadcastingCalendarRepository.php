<?php

namespace WebInterface\Modules\BroadcastingCalendar;

use Infrastructure\MessageCriterionType;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use WebInterface\Models\BroadcastingCalendar;

class BroadcastingCalendarRepository extends Repo{

    private $table;

    function __construct()
    {
        $this->table = "broadcasting";

        parent::__construct($this->table, "WebInterface\\Models\\BroadcastingCalendar");
        $this->currentDate = $this->GetCurrentDate();

    }

 function GetAllBroadcastingData($country,$operator,$service,$promotion,$promotionText,$dateFrom,$dateTo){

     $sql = "SELECT id AS broadcastingid,`datefrom`,`text`,promotion,quantity,dateadded  FROM `broadcasting` WHERE 1= 1 AND datefrom >= '$dateFrom' AND datefrom <= '$dateTo' ";

     if($country !='' && $country>0 ){

         $sql.= " AND country = $country ";
     }

     if($operator !='' && $operator>0 ){

         $sql.= " AND operator = $operator ";
     }

     if($service !='' && $service>0 ){

         $sql.= " AND service = $service ";
     }

     if($promotion !=''){

         $sql.= " AND promotion = '$promotion' ";
     }
     if($promotionText !=''){
         $sql.= " AND text LIKE '%$promotionText%' ";
     }



     $sqlQuery = $this->dbConnection->prepare($sql);
     $sqlQuery->execute();
     return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

 }


    function GetAllBrocastingPromotion(AjaxGrid $ajaxGrid,$Date,$Country,$Operator,$Service,$Promotion)
    {

        $Date = '"'.$Date.'"';

        $sql = <<<query

                   SELECT *,

                CASE WHEN promotion='Balance Plus' THEN '<span class="teaser-icon balanceplus-boardcast-icon"></span>'
                       WHEN promotion='IVR Broadcaster' THEN '<span class="teaser-icon ivr-boardcast-icon"></span>'
                       WHEN promotion='ICB Broadcaster' THEN '<span class="teaser-icon icb-boardcast-icon"></span>'
                       WHEN promotion='SMS Broadcast' THEN '<span class="teaser-icon sms-boardcast-icon"></span>'
                       WHEN promotion='SNS' THEN '<span class="teaser-icon sns-boardcast-icon"></span>'
                       WHEN promotion='Wrong IVR' THEN '<span class="teaser-icon wrongivr-boardcast-icon"></span>'
                       WHEN promotion='Wrong Star(*)' THEN '<span class="teaser-icon wrongstar-boardcast-icon"></span>'
                         END  AS promotionicon FROM broadcasting WHERE datefrom = {$Date}

query;

        if ($Country != '') {

            $sql .= " AND `Country` = $Country ";
        }

        if ($Operator != '') {

            $sql .= " AND Operator = $Operator  ";

        }
        if ($Service != '') {
            $sql .= " AND Service = $Service ";
        }
        if ($Promotion != '') {
            $sql .= " AND promotion = '$Promotion' ";
        }


        $sqlQuery = $this->dbConnection->query($sql);

        $data =  $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->dbConnection->query("SELECT COUNT(*) FROM ($sql) a");

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
        $sql = "SELECT id,name FROM service ORDER BY name ASC";

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


    function GetServiceByOperatorAndCountry($Country,$Operator)
    {
        $sql = "SELECT Service,service.`name` FROM  operatorandservice OS JOIN `service` ON service.`id` = OS.Service WHERE Country =$Country AND Operator =  $Operator";

        $sqlQuery = $this->GetDbConnection()->query($sql);


        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function GetByIdBroadcasting($id, $idFieldName = null)
    {

      $sqlQuery = $this->GetDbConnection()->prepare("SELECT *,DATE_FORMAT(datefrom,'%m/%d/%Y') AS broadcastDate FROM `{$this->table}` WHERE $idFieldName=:$idFieldName");

      $sqlQuery->bindParam(":$idFieldName", $id);

        $sqlQuery->execute();

       return $row = $sqlQuery->fetch(\PDO::FETCH_ASSOC);


    }


    public function CheckPromotionDetails($date, $Country,$Operator,$Service,$ID){


        $sql = "SELECT COUNT(*) as Count FROM `{$this->table}` WHERE datefrom = '$date' AND country = '$Country' AND operator = '$Operator' AND service = '$Service'  ";
        
        if($ID > 0 ){

            $sql.= " AND id <> $ID  ";
        }

        $sqlQuery = $this->GetDbConnection()->prepare($sql);

        $sqlQuery->execute();

        return $row = $sqlQuery->fetch(\PDO::FETCH_COLUMN);

    }



}