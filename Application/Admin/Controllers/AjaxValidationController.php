<?php

namespace Admin\Controllers;

use Admin\Repositories\LoginUserRepository;

use System\MVC\Controller;

class AjaxValidationController extends Controller
{
    function UsernameCheckAction()
    {
        $response = array();

        $response[0] = $_GET["fieldId"];

        $id = $_GET['ID'];

        $usersRepository = new LoginUserRepository();

        $response[1] = $usersRepository->CheckUsernameExists($_GET['fieldValue'], $id) > 0 ? false : true;

        echo json_encode($response);
    }


}