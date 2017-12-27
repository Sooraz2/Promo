<?php

namespace Admin\Controllers;

use Infrastructure\SessionVariables;
use System\MVC\Controller;

abstract class AdminControllerAbstract extends Controller
{
    protected $additionalParams;

    function __construct()
    {
        if (!isset($_SESSION[SessionVariables::$UserID]) || !isset($_SESSION[SessionVariables::$UserType]) || $_SESSION[SessionVariables::$UserType] == 2) {
            Redirect("Login");
        }

        $this->additionalParams = array("Username" => $_SESSION[SessionVariables::$Username],
            "UserType" => $this->ChangeUserType($_SESSION[SessionVariables::$UserType]));

        parent::__construct();

        $this->loader = $this->load;
    }

    function ChangeUserType($userType)
    {
        switch ($userType) {
            case 1:
                return "Admin";
            case 2:
                return "User";
            default:
                return "User";
        }
    }
} 