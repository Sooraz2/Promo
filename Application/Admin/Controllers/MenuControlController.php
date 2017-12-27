<?php

namespace Admin\Controllers;

use Admin\AdminConfirmation;
use Admin\AdminLog;
use Admin\Repositories\LoginUserLogRepository;
use Admin\Repositories\MenuControlRepository;
use Language\English\Logs;
use Infrastructure\SessionVariables;
use Shared\Model\AjaxGrid;
use Shared\Model\LoginUserLog;

class MenuControlController extends AdminControllerAbstract
{

    private $MenuControlRepository;

    function __construct()
    {
        parent::__construct();

        $this->MenuControlRepository = new MenuControlRepository();

        $this->UserLog = new LoginUserLogRepository();

        $this->loginUserLog = new LoginUserLog();

    }

    function IndexAction()
    {

        $accessLevel = "Moderator";

        if (isset($_POST["MenuControlSubmit"])) {

            $menuList = $this->MenuControlRepository->GetAll();

            $menuControlList = array();

            $menuControlArray = json_decode($_POST['MenuAccessData']);

            $accessLevel = $_POST["AccessLevelHidden"];

            foreach ($menuControlArray as $menuControlData) {

                $menuControl = $this->MenuControlRepository->GetById($menuControlData->menuId);

                if($accessLevel == "Moderator"){

                    $menuControl->Moderator = $menuControlData->isChecked ? 1 : 0;

                }elseif($accessLevel=="CustomerCare"){

                    $menuControl->CustomerCare = $menuControlData->isChecked ? 1 : 0;

                }

                array_push($menuControlList, $menuControl);
            }

            $status = $this->MenuControlRepository->Update($menuControlList);

            if ($status) {

                for ($i = 0; $i < count($menuControlArray); $i++) {

                    if ($menuList[$i]->ID == $menuControlList[$i]->ID) {

                        if ($menuList[$i]->Moderator != $menuControlList[$i]->Moderator) {

                            if ($menuControlList[$i]->Moderator == 1)

                                $this->MenuControlRepository->InsertLog(AdminLog::GrantedAccessToModerator($menuList[$i]->Menu));

                            elseif ($menuControlList[$i]->Moderator == 0)

                                $this->MenuControlRepository->InsertLog(AdminLog::RevokeAccessToModerator($menuList[$i]->Menu));
                        }
                    }
                }
            }

            AdminConfirmation::UpdatedMenuControl($status);

        }

        $params["AccessLevel"] = $accessLevel;

        $this->load->View("MenuControl/Index", $params);
    }

    function ListAction()
    {
        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        $accessLevel  = array($this->language->$_GET["AccessLevel"],$_GET["AccessLevel"]);

        echo json_encode($this->MenuControlRepository->FindAll($ajaxGrid, $accessLevel, $this->language->LanguageClass));
    }

    function ChangeMenuAccessAction()
    {

        $menu = $this->MenuControlRepository->UpdateSingleField($_POST["userType"], $_POST["changeTo"], $_POST["ID"]);

        $data["success"] = $menu ? true : false;

        echo json_encode($data);

    }


}