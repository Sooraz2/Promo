<?php
/**
 * Created by PhpStorm.
 * User: Epayy
 * Date: 12/7/2014
 * Time: 11:50 AM
 */

namespace Repositories;


use Admin\Models\LoginLog;
use Infrastructure\SessionVariables;
use Shared\Model\AjaxGrid;
use System\Repositories\Repo;
/*for anyone who tries to login insert logs*/
class LoginLogsTableRepository extends Repo
{
    private $table;

    function __construct()
    {
        $this->table = "login_logs";

        $this->UserLogModel = new LoginLog();
        parent::__construct($this->table, "Admin\\Models\\LoginLog");
    }

    public function Save(LoginLog $loginLog)
    {
        try {
            $this->Insert($loginLog, array("ID"));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function Update(LoginLog $loginLog)
    {
        try {
            $this->UpdateTable($loginLog, array("id"), null, "login_ip");
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function SaveOrUpdateLoginDetails(LoginLog $loginLog)
    {

        $log = $this->WhereReturnModel(array("login_ip" => $loginLog->login_ip));
        if (count($log) == 1) {
            $log = $log[0];
            $loginLog->login_failed = ++$log->login_failed;
            $loginLog->last_failed_login = $this->GetCurrentDateTime();
            $this->Update($loginLog);

        } else {
            $loginLog->login_failed = 1;
            $loginLog->last_failed_login = $this->GetCurrentDateTime();
            $this->Save($loginLog);
        }
        return $loginLog;
    }

    public function ClearLoginFailed(LoginLog $loginLog){

        $log = $this->WhereReturnModel(array("login_ip" => $loginLog->login_ip));
        if (count($log) == 1) {
            $log = $log[0];
            $log->login_failed = 0;
            $log->last_successful_login = $this->GetCurrentDateTime();
            $this->Update($log);
        }
    }


} 