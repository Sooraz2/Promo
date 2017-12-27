<?php
/**
 * Created by PhpStorm.
 * User: Epayy
 * Date: 12/7/2014
 * Time: 11:50 AM
 */

namespace Admin\Repositories;


use Infrastructure\CookieVariable;
use Infrastructure\DefaultLanguages;
use Infrastructure\SessionVariables;
use Shared\Model\AjaxGrid;
use WebInterface\Models\LoginUserLog;
use System\Repositories\Repo;

class LoginUserLogRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "login_user_logs";

        $this->UserLogModel = new LoginUserLog();
        parent::__construct($this->table, "Admin\\Models\\LoginUserLog");
    }

    public function Save($loginUserLog)
    {
        try {
            $this->Insert($loginUserLog, array("ID"));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function FindAll(AjaxGrid $ajaxGrid, $userId)
    {
        $sql = "SELECT {$this->table}.ID as LoginUserLogID, Ip , Username,";
        if(isset($_COOKIE[CookieVariable::$BalancePlusLanguage]) && $_COOKIE[CookieVariable::$BalancePlusLanguage]==DefaultLanguages::$DefaultLanguage){
            $sql .="{$this->table}.Action AS Action, ";
        }else{
            $sql .="{$this->table}.Action AS Action, ";
        }
        $sql .="Datetime FROM {$this->table}
                INNER JOIN login_user ON login_user.ID={$this->table}.UserID WHERE login_user.ID=$userId
                ORDER BY $ajaxGrid->sortExpression $ajaxGrid->sortOrder LIMIT $ajaxGrid->offset, $ajaxGrid->rowNumber";

        $sqlQuery = $this->GetDbConnection()->query($sql);

        $data = $sqlQuery->fetchAll(\PDO::FETCH_ASSOC);

        $sqlQuery = $this->GetDbConnection()->query("SELECT COUNT(*) FROM {$this->table}
                            INNER JOIN login_user ON login_user.ID={$this->table}.UserID WHERE login_user.ID=$userId");
        $rowCount = $sqlQuery->fetch();

        $list['RowCount'] = $rowCount[0];
        $list['Data'] = $data;
        $list['PageNumber'] = $ajaxGrid->pageNumber;

        return $list;
    }

} 