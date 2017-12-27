<?php
/**
 * Created by PhpStorm.
 * User: Love Shankar Shresth
 * Date: 3/8/2015
 * Time: 11:58 AM
 */

namespace Admin\ViewModel;


use System\MVC\ModelAbstract;

class ProductView extends  ModelAbstract{

    public $ID;

    public $ServiceCategory;

    public $ServiceType;

    public $Service;

    public $Top;

    public $ExtraTop;

    public $InDevelopment;

    public $Manager;
}