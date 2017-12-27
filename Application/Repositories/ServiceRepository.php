<?php

namespace Repositories;


use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use Admin\Models\MenuControl;
use WebInterface\Models\Service;

class ServiceRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "service";

        parent::__construct($this->table, "WebInterface\\Models\\Service");
    }

    public function Save(Service $operator)
    {
        try {

            $this->Insert($operator, array("ID"));

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    public function Update(Service $operator)
    {

        try {
                $this->UpdateTable($operator, array("id"),'id');
            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    function FindAll(AjaxGrid $ajaxGrid)
    {

        $sql = "SELECT * FROM $this->table  ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";


        $sqlQuery = $this->GetDbConnection()->query($sql);


        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table}");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

    function CheckServiceExists($service, $id)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE name='$service'";

        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND id<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }

} 