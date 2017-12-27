<?php
/**
 * Created by PhpStorm.
 * User: Love Shankar Shresth
 * Date: 3/1/2015
 * Time: 3:24 PM
 */

namespace WebInterface\Modules\ChangePassword;


use Infrastructure\Encryption;
use Infrastructure\SessionVariables;
use Language\English\Message;
use Admin\Repositories\LoginUserLogRepository;
use Admin\Repositories\LoginUserRepository;
use Language\English\Logs;
use System\MVC\Controller;

class ChangePasswordController extends Controller {

    public  $loginUserRepository;

    function __construct()
    {
        parent::__construct();
        $this->loginUserRepository = new LoginUserRepository();

    }

    function IndexAction()
    {
        $oldPassword = $_POST["OldPassword"];
        $newPassword = $_POST["NewPassword"];
        $confirmPassword = $_POST["ConfirmPassword"];

        $oldPasswordMatch = $this->loginUserRepository->CheckIfOldPasswordMatches($_SESSION[SessionVariables::$UserID], Encryption::EncryptPassword($oldPassword));


        if (!$oldPasswordMatch) {
            $_SESSION[SessionVariables::$ConfirmationMessage] =  $this->language->OldPasswordDoesNotMatch;
            $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";
            header("Location:".$_POST["RedirectUrl"]);
            exit;
        }


        if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*(_|[^\w]))(?=.*[A-Z]).{9,20}$/", $newPassword)){
            $_SESSION[SessionVariables::$ConfirmationMessage] = $this->language->PasswordValidationFailed;
            $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";
            header("Location:".$_POST["RedirectUrl"]);
            exit;
        }

        if ($newPassword == $confirmPassword) {
            $status = $this->loginUserRepository->UpdatePassword($_SESSION[SessionVariables::$UserID], Encryption::EncryptPassword($newPassword));

            switch($status):
                case(1):
                    $_SESSION[SessionVariables::$ConfirmationMessage] =$this->language->SuccessfullyChangedPassword ;
                    $_SESSION[SessionVariables::$ConfirmationMessageType] = "Success";
                    break;
                case(-1):
                    $_SESSION[SessionVariables::$ConfirmationMessage] =$this->language->MaximumPasswordReuseException ;
                    $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";
                    break;
                case(0):
                    $_SESSION[SessionVariables::$ConfirmationMessage] = $this->language->FailedChangingPassword;
                    $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";
                    break;
            endswitch;

        } else {

            $_SESSION[SessionVariables::$ConfirmationMessage] =  $this->language->ConfirmPasswordDoesNotMatch;
            $_SESSION[SessionVariables::$ConfirmationMessageType] = "Failed";

        }

        header("Location:".$_POST["RedirectUrl"]);
        exit;

    }



    function FormAction()
    {
        $pattern["redirectUrl"] = substr($_GET["RedirectUrl"],strrpos($_GET["RedirectUrl"],"/")+1);

        $this->load->View("ChangePassword/Form",$pattern);
    }
}