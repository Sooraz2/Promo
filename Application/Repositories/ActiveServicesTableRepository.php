<?php

namespace Repositories;

use Admin\Models\LoginUser;
use Infrastructure\SessionVariables;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use WebInterface\Models\ActiveServices;
use WebInterface\Models\TariffPlan;

class ActiveServicesTableRepository extends Repo
{
    private $table;


    function __construct()
    {
        $this->table = "criterion_services";

        parent::__construct($this->table, "WebInterface\\Models\\ActiveServices");

    }

    public function Save(ActiveServices $activeServices)
    {
        try {

            $id = $this->Insert($activeServices, array("id"));

            return $id;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function Update(ActiveServices $activeServices)
    {
        try {

            $this->UpdateTable($activeServices, array("id"), "id");

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function DeleteActiveServices($id)
    {
        $this->GetDbConnection()->beginTransaction();

        try {

            $this->Delete($id, "id");

            $this->GetDbConnection()->commit();

            return true;

        } catch (\Exception $e) {

            $this->GetDbConnection()->rollBack();

            return false;

        }
    }

    function DeleteAll()
    {
        $sql = "DELETE FROM {$this->table}";
        $sth = $this->dbConnection->prepare($sql);
        return ($sth->execute());
    }

    function DeleteSelected($ids)
    {
        $ids = implode($ids, ",");
        $sql = "DELETE FROM {$this->table} where id in ($ids)";
        $sth = $this->dbConnection->prepare($sql);
        return ($sth->execute());
    }

    function DeleteSelectedItem($delId)
    {
        $sql = "";

        if(count($delId)>0){
            foreach ($delId as $id) {
                $sql .= "DELETE FROM {$this->table} WHERE id IN($id); ";
            }
            $sth = $this->dbConnection->prepare($sql);
            return ($sth->execute());
        }else{
            return false;
        }
    }


    public function GetByDescription($description)
    {
        $sqlQuery = $this->GetDbConnection()->prepare("SELECT * FROM `{$this->table}` WHERE `description`='$description'");

        $sqlQuery->execute();

        $model = new ActiveServices();

        while ($row = $sqlQuery->fetch(\PDO::FETCH_ASSOC)) {
            $model->MapParameters($row);
        }
        return $model;
    }

    function CheckActiveServicesIDExists($newId, $id)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE id=$newId";

        if ($id != '' && $id != null) {
            $sql .= " AND id<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }

    function GetAllUsedOptions($criterionTypeID){

        $sql = "SELECT DISTINCT criterion_id, {$this->table}.* FROM `messages_criterion`
                INNER JOIN {$this->table} ON messages_criterion.`criterion_id` = {$this->table}.`id`
                WHERE messages_criterion.`criterion_type_id`='$criterionTypeID'";

        $sth = $this->dbConnection->prepare($sql);

        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_ASSOC);

    }
}