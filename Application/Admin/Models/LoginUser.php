<?php

namespace Admin\Models;

use System\MVC\ModelAbstract;

class LoginUser extends ModelAbstract
{
    public $ID;

    public $Name;

    public $Email;

    public $Username;

    public $Password;

    public $UserType;

    public $SendNotification;

    public $LastLogin;

    public $DateCreated;
}