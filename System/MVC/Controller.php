<?php

namespace System\MVC;

use Admin\Repositories\MenuControlRepository;
use Infrastructure\InterfaceVariables;
use Infrastructure\LanguageLog;
use Infrastructure\MessageLog;
use Admin\Repositories\LoginUserRepository;
use Infrastructure\SessionVariables;
use Repositories\MenuControlTableRepository;
use System\Core\Loader;


abstract class Controller
{
    private static $instance;
    protected $language;
    public $loginUserRepoObj;

    public function __construct()
    {
        global $langConfig;
        $this->language = $langConfig->languageClass;

        self::$instance =& $this;
        $this->load = new Loader();
        $this->checkAndSetTime();

        LanguageLog::SetLog();
        MessageLog::SetMessage();

        $MenuControlRepo = new MenuControlTableRepository();

        global $uri;

        if (isset($_SESSION[SessionVariables::$UserType]) && $_SESSION[SessionVariables::$UserType] == 2) {
            // $MenuControlRepo = new MenuControlRepository();
            //  $this->load->setAllHiddenMenu($MenuControlRepo->GetAllHiddenMenu());
        } else {
            $this->load->setAllHiddenMenu(array());
        }

        $parts = trim($uri, '/');
        $parts = explode('/', $parts);
        $slug = array_shift($parts);
        if (isset($_SESSION["UserType"])):
            switch ($_SESSION["UserType"]):
                case(1):
                    $UserType = "Administrator";
                    break;
                case(2):
                    $UserType = "Moderator";
                    break;
                case(3):
                    $UserType = "Operator";
                    break;
                case(4):
                    $UserType = "CustomerCare";
                    break;
                default:
                    $UserType = "";
                    break;
            endswitch;
            if ($UserType == "Administrator" ||$UserType == "Moderator" || $UserType == "Operator" || $slug == "LogOut" || $slug == "Login" || $slug == "ChangePassword") {

            } else if ($UserType == "Moderator2") {
                $count = $MenuControlRepo->Count(array("MenuSlug" => $slug, $UserType => 1));

                if (property_exists($this->load->configArray->AllocationCriteria, $slug) ||
                    property_exists($this->load->configArray->BlackListControllers, $slug)
                ) {
                    $count->Count = 1;
                }

                $param = array("slug" => $slug);
                if ($count->Count == "0") {
                    $this->load->View('NoAccess/Index', $param);
                    exit;
                }
            }
        endif;
    }

    protected function CheckIfPasswordExpired($userID, $redirectURI = "ActiveTeasers")
    {
        $this->loginUserRepoObj = new LoginUserRepository();
        $passwordExpired = $this->loginUserRepoObj->CheckPasswordExpiry($userID);
        if (!!$passwordExpired->Expired) {
            $this->load->View('Login/ChangePassword', array("RedirectURI" => $redirectURI));
            exit;
        }
    }

    private function checkAndSetTime()
    {
        $loggedInTime = null;
        if (isset($_SESSION[SessionVariables::$LoggedInTime]))
            $loggedInTime = $_SESSION[SessionVariables::$LoggedInTime];

        if (is_null($loggedInTime))
            $_SESSION[SessionVariables::$LoggedInTime] = date("Y-m-d h:i:s");
        else {
            $currentTime = date("Y-m-d h:i:s");
            $start_date = new \DateTime($loggedInTime);
            $since_start = $start_date->diff(new \DateTime($currentTime));
            if ((int)$since_start->i >= (int)InterfaceVariables::$SessionTimeOut) {
                unset($_SESSION[SessionVariables::$UserID]);
                unset($_SESSION[SessionVariables::$Username]);
                unset($_SESSION[SessionVariables::$UserType]);
                unset($_SESSION[SessionVariables::$LoggedInTime]);
                setcookie("LastVisitedUrlChinguitel", "", time() - 10, "/");

                session_unset();
                session_destroy();
            } else
                $_SESSION[SessionVariables::$LoggedInTime] = date("Y-m-d h:i:s");
        }
    }

}