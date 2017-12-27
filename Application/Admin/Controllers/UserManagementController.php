<?php

namespace Admin\Controllers;

use Admin\Models\LoginUser;
use Infrastructure\Encryption;
use Infrastructure\Message;
use Infrastructure\PHPFormToken;
use Infrastructure\SessionVariables;
use Admin\Repositories\LoginUserLogRepository;
use Admin\Repositories\LoginUserRepository;
use Shared\Model\AjaxGrid;
use Language\English\Logs;
use Admin\AdminLog;
use Admin\AdminConfirmation;

class UserManagementController extends AdminControllerAbstract
{
    private $loginUserRepository;

    private $FormToken;

    function __construct()
    {
        parent::__construct();

        $this->loginUserRepository = new LoginUserRepository();

        $this->UserLogRepository = new LoginUserLogRepository();

        $this->FormToken = new PHPFormToken();
    }

    private function CheckPasswordValidation($loginUser, $isPasswordField)
    {

        if ($isPasswordField && !preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*(_|[^\w]))(?=.*[A-Z]).{9,20}$/", $loginUser->Password)) {

            $_SESSION[SessionVariables::$ConfirmationMessage] = $this->language->PasswordValidationFailed;

            $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";

            return false;

        }
        return true;

    }

    function IndexAction()
    {
        $loginUser = new LoginUser();

        if (isset($_POST['Username']) && isset($_POST["UserManagementFormToken"])) {

            if($this->FormToken->GetFormToken("UserManagementFormToken") == $_POST['UserManagementFormToken']) {

                $this->FormToken->UnsetFormToken("UserManagementFormToken");

                $loginUser->MapParameters($_POST);

                $isNewUser = $loginUser->ID == null && $loginUser->ID == 0;

                $isChangePassword = isset($_POST["ChangePassword"]);

                if ($this->CheckPasswordValidation($loginUser, ($isNewUser || $isChangePassword))) {

                    $loginUser->Password = Encryption::EncryptPassword($loginUser->Password);

                    $loginUser->DateCreated = $this->loginUserRepository->GetCurrentDateTime();

                    $loginUser->PasswordUpdatedDate = $this->loginUserRepository->GetCurrentDate();

                    if (!$isNewUser) {

                        $status = $this->loginUserRepository->Update($loginUser, $isChangePassword, AdminLog::EditedUser($loginUser));

                        AdminConfirmation::UpdatedUser($status);

                    } else {

                        $status = $this->loginUserRepository->Save($loginUser, AdminLog::SavedUser($loginUser));

                        AdminConfirmation::SavedUser($status);

                    }
                }
            }
        }

        $params = (array)$this->additionalParams;

        if (isset($_POST['UserLogSubmit'])) {

            $params['userId'] = $_POST["UserID"];

            $selectedUser=$this->loginUserRepository->GetById($_POST['UserID']);

            $params['UserType'] =$selectedUser->UserType;
        }

        $this->load->View("UserManagement/Index", $params);
    }

    function FormAction()
    {
        $loginUser = new LoginUser();

        $saveOrUpdate = "Save";

        if (isset($_GET['ID'])) {

            $saveOrUpdate = "Update";

            $loginUser = $this->loginUserRepository->GetById($_GET['ID']);

        }

        $params["loginUser"] = $loginUser;

        $params["saveOrUpdate"] = $saveOrUpdate;

        $this->FormToken->SetFormToken("UserManagementFormToken");

        $params["formToken"] = $this->FormToken->GetFormToken("UserManagementFormToken");

        $this->load->View("UserManagement/Form", $params);
    }

    function ListAction()
    {
        $ajaxGrid = new AjaxGrid();

        $ajaxGrid->MapParameters($_GET);

        echo json_encode($this->loginUserRepository->FindAll($ajaxGrid, $this->language->LanguageClass));
    }

    function DeleteAction()
    {

        $loginUser = $this->loginUserRepository->GetById($_POST["ID"]);

        $status = null;

        if ($loginUser->ID != 1) {

            $status = $this->loginUserRepository->DeleteUser($_POST["ID"], AdminLog::DeletedUser($loginUser));

            AdminConfirmation::DeletedUser($status);

        } else {

            AdminConfirmation::CannotDeleteUser();

        }

        Redirect("Admin/UserManagement");
    }

}