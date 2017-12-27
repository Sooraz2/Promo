<?php

namespace Shared\Controllers;

use Admin\Repositories\LoginUserRepository;
use Infrastructure\SessionVariables;
use Admin\Repositories\LoginUserLogRepository;
use Shared\SharedLog;
use System\MVC\Controller;

class LogOutController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->UserLogRepository = new LoginUserLogRepository();

        $this->loginUserRepository = new LoginUserRepository();
    }

    function IndexAction()
    {

        $status = $this->loginUserRepository->UserLoggedOutLog(SharedLog::LoggedOut());
        if($status) {
            unset($_SESSION[SessionVariables::$UserID]);
            unset($_SESSION[SessionVariables::$Username]);
            unset($_SESSION[SessionVariables::$UserType]);

            session_unset();
            session_destroy();
        }
        Redirect("Login");
    }
}