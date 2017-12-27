<?php

namespace Admin\Models;

use System\MVC\ModelAbstract;

class LoginLog extends ModelAbstract
{
    public $id;

    public $login_ip;

    public $login_failed;

    public $last_failed_login;

    public $last_successful_login;
}