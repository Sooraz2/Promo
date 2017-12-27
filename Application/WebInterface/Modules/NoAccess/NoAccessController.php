<?php

namespace WebInterface\Modules\Controllers;

use System\MVC\Controller;

class NoAccessController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }

    function IndexAction()
    {
       $this->load->View("NoAccess/Index");
    }

} 