<?php

namespace Shared\Controllers;

use Infrastructure\SessionVariables;
use System\MVC\Controller;


abstract class WebInterfaceControllerAbstract extends Controller
{
    function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION[SessionVariables::$UserID]) || !isset($_SESSION[SessionVariables::$UserType])) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                header('Content-Length: 0');
                exit;
            } else
                Redirect("Login");
        }

    }


}