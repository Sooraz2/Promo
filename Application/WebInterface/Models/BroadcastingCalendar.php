<?php

namespace WebInterface\Models;

use System\MVC\ModelAbstract;

class BroadcastingCalendar extends ModelAbstract
{
    public $id;

    public $datefrom;

    public $dateto;

    public $country;

    public $operator;

    public $service;

    public $promotion;

    public $quantity;

    public $text;

    public $dateadded;
    
    public $comments;

}