<?php

namespace WebInterface\Modules\OperatorAndServicesRelation;


use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use Admin\Models\MenuControl;
use WebInterface\Models\Country;
use WebInterface\Models\OperatorAndServicesRelation;

class OperatorAndServicesRelationRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "operatorandservice";

        parent::__construct($this->table, "WebInterface\\Models\\OperatorAndServicesRelation");
    }

    public function Save(OperatorAndServicesRelation $operatorAndServicesRelation)
    {
        try {

            $this->Insert($operatorAndServicesRelation, array("ID"));

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    public function Update(OperatorAndServicesRelation $operatorAndServicesRelation)
    {

        try {
                $this->UpdateTable($operatorAndServicesRelation, array("ID"));
            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    function FindAll(AjaxGrid $ajaxGrid)
    {



        $sql = "SELECT OS.*, country.name AS CountryName,operator.`name` AS OperatorName, service.`name`AS ServiceName FROM `operatorandservice` OS
                JOIN `country` ON country.id = OS.Country
                JOIN `operator` ON operator.`id` = OS.Operator
                JOIN `service` ON service.`id` = OS.Service ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";




        $sqlQuery = $this->GetDbConnection()->query($sql);


        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM `operatorandservice` OS
                                                                JOIN `country` ON country.id = OS.Country
                                                                JOIN `operator` ON operator.`id` = OS.Operator
                                                                JOIN `service` ON service.`id` = OS.Service");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

    function CheckCountryExists($country, $id)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE name='$country'";

        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND id<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }


    function GetAllCountry(){

        $sql = "SELECT * FROM country ";


        $sqlQuery = $this->GetDbConnection()->query($sql);

       return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

    }
    function GetAllOperator(){

        $sql = "SELECT * FROM operator";

        $sqlQuery = $this->GetDbConnection()->query($sql);

       return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }

    function GetAllService(){

        $sql = "SELECT * FROM service ";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        return $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }

} 