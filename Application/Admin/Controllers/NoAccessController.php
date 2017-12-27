<?php

namespace Admin\Controllers;

use System\MVC\Controller;

class NoAccessController extends Controller
{
    function IndexAction()
    {
       $this->load->TwigView("Noaccess/index");
    }

} 