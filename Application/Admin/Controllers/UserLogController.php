<?php

namespace Admin\Controllers;

use Admin\Repositories\LoginUserLogRepository;
use Shared\Model\AjaxGrid;

class UserLogController extends AdminControllerAbstract
{
    private $loginUserLogRepository;

    function __construct()
    {
        parent::__construct();

        $this->loginUserLogRepository = new LoginUserLogRepository();
    }

    function ListAction()
    {
        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        $userID = $_GET['id'];

        echo json_encode($this->loginUserLogRepository->FindAll($ajaxGrid, $userID));
    }

} 