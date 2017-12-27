<?php

namespace WebInterface\Modules\Logs;

use Infrastructure\CookieVariable;
use Infrastructure\DefaultLanguages;
use Infrastructure\SessionVariables;
use Admin\Repositories\LoginUserLogRepository;
use Shared\Controllers\WebInterfaceControllerAbstract;
use Shared\Model\AjaxGrid;

class LogsController extends WebInterfaceControllerAbstract
{
    private $loginUserLogRepository;

    function __construct()
    {
        parent::__construct();

        if ($_SESSION[SessionVariables::$UserType] == 1)
            Redirect("Admin/UserManagement");

        $this->loginUserLogRepository = new LoginUserLogRepository();
    }

    function IndexAction()
    {
        $params["userId"] = $_SESSION[SessionVariables::$UserID];

        $this->load->View("Logs/Index", $params);
    }

    function ListAction()
    {
        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        $userID = $_GET['id'];

        $language = isset($_COOKIE[CookieVariable::$BalancePlusLanguage]) ? $_COOKIE[CookieVariable::$BalancePlusLanguage] : DefaultLanguages::$DefaultLanguage;

        echo json_encode($this->loginUserLogRepository->FindAll($ajaxGrid, $userID, $language));
    }
} 