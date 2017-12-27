<?php

namespace Shared\Controllers;

use Admin\Models\LoginLog;
use Admin\Models\LoginUser;
use Admin\Repositories\LoginUserLogRepository;
use Admin\Repositories\LoginUserRepository;
use Infrastructure\CookieVariable;
use Infrastructure\DefaultLanguages;
use Infrastructure\Encryption;
use Infrastructure\SessionVariables;
use Shared\Repositories\LoginLogsRepository;
use Shared\SharedLog;
use System\MVC\Controller;
use Language\English\Logs;
use WebInterface\Modules\TeaserManagement\TeaserManagementRepository;


class LoginController extends Controller
{
    private $errorMessage = "";

    private $MaximumWrongPasswordAttempts = 5;

    private $log;

    public function __construct()
    {
        parent::__construct();

        $this->UserLogRepository = new LoginUserLogRepository();
        $this->LoginLog = new LoginLog();
        $this->LoginLogRepository = new LoginLogsRepository();
        $this->loginUserRepository = new LoginUserRepository();
        $this->log = new Logs();
        if (!isset($_COOKIE[CookieVariable::$BalancePlusLanguage])) {
            setcookie(CookieVariable::$BalancePlusLanguage, DefaultLanguages::$DefaultLanguage, time() + (24 * 60 * 60 * 1000), '/');
        }
    }

    private function WriteOnLoginFailure()
    {
        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        try{
            syslog(LOG_INFO,replaceString($this->log->failedLogin, array(  ":ip"=> GetClientIp(),
                ":datetime"=>$this->LoginLogRepository->GetCurrentDateTime(),
                ":marker"=>"Failed Authentication",
                ":login"=>$_POST["Username"],
                ":password"=>$_POST["Password"]
            )));
        }catch(\Exception $e){
        }
    }

    private function CheckFailedLoginTime()
    {

        $loginLog = $this->LoginLogRepository->WhereReturnModel(array("login_ip" => ip2long(GetClientIp())));

        $loginLog = count($loginLog) > 0 ? $loginLog[0] : array();

        $currentTime = $this->LoginLogRepository->GetCurrentDateTime();

        $disableLogin = false;

        if (isset($loginLog->last_failed_login) && isset($loginLog->login_failed) && $loginLog->login_failed >= $this->MaximumWrongPasswordAttempts) {

            $differenceTimeMinutes = (strtotime($currentTime) - strtotime($loginLog->last_failed_login)) / 60;

            if ($differenceTimeMinutes < 20)
                $disableLogin = true;
        }
        if ($disableLogin) {

            $this->load->View('Login/Index', array("errorMessage" => $this->errorMessage,
                "LoginLog" => $loginLog,
                "DateTimeNow;" => $currentTime,
                "DisableLogin" => $disableLogin
            ));
            exit;
        }
    }


    function IndexAction()
    {
        $this->CheckFailedLoginTime();

        if (isset($_POST['LoginSubmit']) && isset($_POST['Username']) && isset($_POST['Password'])) {

            $loginUser = new LoginUser();

            $loginUser->MapParameters($_POST);

            $loginUser->Password = Encryption::EncryptPassword($loginUser->Password);

            $loginSuccessUser = $this->loginUserRepository->CheckLogin($loginUser);

            if (!$loginSuccessUser || !($loginSuccessUser instanceof LoginUser)) {

                $this->errorMessage = "Invalid Username or Password";
                $this->LoginLog->login_ip = ip2long(GetClientIp());
                $this->LoginLog = $this->LoginLogRepository->SaveOrUpdateLoginDetails($this->LoginLog);
                $this->WriteOnLoginFailure();

            } else {

                $this->LoginLog->login_ip = ip2long(GetClientIp());

                $_SESSION[SessionVariables::$Username] = $loginSuccessUser->Username;

                $_SESSION[SessionVariables::$UserID] = $loginSuccessUser->ID;

                $_SESSION[SessionVariables::$UserType] = $loginSuccessUser->UserType;

                $this->LoginLogRepository->ClearLoginFailed($this->LoginLog, SharedLog::LoggedIn());

                if (isset($_SESSION[SessionVariables::$Username])) {
                  //  $teaserRepo = new TeaserManagementRepository();
                   // $teaserRepo->GetAllActiveTeaserCount();
                }

                $redirectURI = "BroadcastingCalendar";

                if ($_SESSION[SessionVariables::$UserType] == 4) {
                    $redirectURI = "BroadcastingCalendar";
                }
                if (isset($_COOKIE['LastVisitedUrlChinguitel']) && $_COOKIE['LastVisitedUrlChinguitel'] != '' && $_COOKIE['LastVisitedUrlChinguitel'] != '/Login' && $_COOKIE['LastVisitedUrlChinguitel'] != "/") {

                    $redirectURI = substr($_COOKIE['LastVisitedUrlChinguitel'], 1);
                }
                $explode = explode('/', $redirectURI);
                $base_dir = explode("/",BASE_URL);
                $base_dir = $base_dir[count($base_dir) - 2];

                if(count($explode) > 0){
                    if($explode[0] == $base_dir){
                        unset($explode[0]);
                        $redirectURI = implode("/", $explode);
                    }

                }

                Redirect($redirectURI);
            }
        } else {
            if (isset($_SESSION[SessionVariables::$UserType])) {
                $redirectURI = "BroadcastingCalendar";
                if ($_SESSION[SessionVariables::$UserType] == 4) {
                    $redirectURI = "BroadcastingCalendar";
                }

                if (isset($_COOKIE['LastVisitedUrlChinguitel']) && $_COOKIE['LastVisitedUrlChinguitel'] != '') {

                    $redirectURI = substr($_COOKIE['LastVisitedUrlChinguitel'], 1);

                }

                $explode = explode('/', $redirectURI);
                $base_dir = explode("/",BASE_URL);
                $base_dir = $base_dir[count($base_dir) - 2];

                if(count($explode) > 0){
                    if($explode[0] == $base_dir){
                        unset($explode[0]);
                        $redirectURI = implode("/", $explode);
                    }

                }
                Redirect($redirectURI);
            }
        }
        $this->CheckFailedLoginTime();
        $this->load->View('Login/Index', array("errorMessage" => $this->errorMessage));
    }

    function CheckLoggedInFromCookie()
    {
        if (isset($_COOKIE['Username']) && isset($_COOKIE['Password'])) {
            $username = $_COOKIE['Username'];
            $password = $_COOKIE['Password'];
            $loginUser = new LoginUser();
            $loginUser->Username = $username;
            $loginUser->Password = $password;
            $loginSuccessUser = ($this->loginUserRepository->CheckLogin($loginUser));
            if ($loginSuccessUser && ($loginSuccessUser instanceof LoginUser)) {
                $_SESSION[SessionVariables::$Username] = $loginSuccessUser->Username;
                $_SESSION[SessionVariables::$UserID] = $loginSuccessUser->ID;
                $_SESSION[SessionVariables::$UserType] = $loginSuccessUser->UserType;
                if ($_SESSION[SessionVariables::$UserType] == 1)
                    Redirect("Admin/UserManagement");
                else
                    Redirect("Admin/UserManagement");
            } else {
                $this->load->View('Login/Index');
            }
        }
    }
}