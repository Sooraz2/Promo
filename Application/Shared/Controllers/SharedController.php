<?php

namespace Shared\Controllers;

use System\MVC\Controller;

class SharedController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    function ShowHideLeftPanelAction()
    {
        if ($_GET['ShowHideLeftPanel'] == "Show") {
            $_SESSION['ShowHideLeftPanel'] = "Show";
        } else {
            $_SESSION['ShowHideLeftPanel'] = "Hide";
        }
        echo json_encode(array("status" => "success"));
    }
} 