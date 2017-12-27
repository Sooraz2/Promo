<?php

namespace Repositories;


use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use Admin\Models\MenuControl;
use WebInterface\Models\Country;

class CountryRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "country";

        parent::__construct($this->table, "WebInterface\\Models\\Country");
    }

    public function Save(Country $country)
    {
        try {

            $this->Insert($country, array("ID"));

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    public function Update(Country $country)
    {

        try {
                $this->UpdateTable($country, array("id"),'id');
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

    function CheckCountryExists($country, $id)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE name='$country'";

        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND id<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }

} 