<?php

namespace Repositories;


use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
use Admin\Models\MenuControl;

class MenuControlTableRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "menu_control";

        parent::__construct($this->table, "Admin\\Models\\MenuControl");
    }

    public function Save(MenuControl $menu_control)
    {
        try {

            $this->Insert($menu_control, array("ID"));

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    public function Update($menuControlList)
    {
        try {

            foreach ($menuControlList as $menuControl) {
                $this->UpdateTable($menuControl, array("Menu", "MenuSlug","MenuFr"));
            }

            return true;
        } catch (\Exception $e) {

            return false;
        }

    }

    public function GetBySlug($slug, $id = null)
    {
        $sql = "SELECT * from {$this->table}
                where MenuSlug = '$slug' ";
        if ($id != '' && $id > 0 && $id != null) {
            $sql .= " AND ID<>$id";
        }
        $sql .= " limit 0,1";
        $model = new MenuControl();
        $sqlQuery = $this->GetDbConnection()->query($sql);

        while ($row = $sqlQuery->fetch(\PDO::FETCH_ASSOC)) {
            $model->MapParameters($row);
        }
        return $model;
    }

    function FindAll(AjaxGrid $ajaxGrid, $accessLevel, $language)
    {

        $sql = "SELECT *,'$accessLevel[0]' as `UserType`,`$accessLevel[1]` as Access
                FROM {$this->table}  ";

        switch($accessLevel[1]){

            case "Moderator":
                    $sql .= " WHERE MenuSlug NOT IN('UserManagement','MenuControl','UserLog')";
                break;

            case "CustomerCare":
                    $sql .= " WHERE MenuSlug NOT IN('UserManagement','MenuControl','UserLog') AND `CustomerCare` IS NOT NULL ";
                break;

            default:
                $sql .= " WHERE MenuSlug NOT IN('UserManagement','MenuControl','UserLog')";

        }


        $orderSql = "ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset,$ajaxGrid->rowNumber";
        
        $sql .= $orderSql;
        
        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT Count(*) FROM {$this->table}");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

    public function UpdateSingleField($field, $fieldValue, $ID)
    {
        $updateSql = "UPDATE `{$this->table}` SET $field = :fieldValue where ID = :ID";
        $sqlQuery = $this->GetDbConnection()->prepare($updateSql);
        $sqlQuery->bindValue(":ID", $ID);
        $sqlQuery->bindValue(":fieldValue", $fieldValue);
        return $sqlQuery->execute();


    }

} 