<?php

namespace WebInterface\Modules\ReportData;

use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use WebInterface\Models\ReportData;


class ReportDataRepository extends Repo
{
    private $table;

    function __construct()
    {

        $this->table = "inflow_stored_procudure";

        parent::__construct($this->table, "WebInterface\\Models\\ReportData");
    }



    function FindAll(AjaxGrid $ajaxGrid)
    {

        $sql = "SELECT * FROM inflow_stored_procudure LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);


        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM `inflow_stored_procudure` ");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }


    public function Save(ReportData $reportData)
    {
        try {

            $this->Insert($reportData, array("id"));

            return true;
        } catch (\Exception $e) {

            echo     $e->getMessage();
            return false;
        }

    }

    function CheckProcudureExists($procudure, $id)
    {
        $procudure2 = "[VCHReports].[dbo].[".$procudure."] ";

        $sql = "SELECT COUNT(*) FROM {$this->table}  WHERE name='$procudure' OR name ='$procudure2' ";

        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND id<>$id";
        }

        return $this->GetDbConnection()->query($sql)->fetchColumn();
    }




}