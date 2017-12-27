<?php

namespace WebInterface\Models;

use System\MVC\ModelAbstract;

class Teaser extends ModelAbstract
{
    public $id;

    public $text;

    public $stamp;

    public $counter;

    public $is_active;

    public $is_deleted;

    public $chars;

    public $lang;

    public $is_perm_deleted;

    public $is_interactiv;

    public $limit_of_shows;

    public $activation_code;

    public $broadcast_date_sel_type;/* custom selection : 0 ,only start date : 1, start and end dates: 2*/

    public $service;

    public $is_high_priority;

    public $is_whitelist;

    public $is_termless;

    public $sms_text;

    public $send_sms;

    public $reference_id;

    public $updated_by;

    public $updated_date;

    public $is_modified;

    public $service_code;

    public $paused_date;

    public $removal_date;

    public $removed_by;

    public $two_step_activation;

    public $two_step_activation_text;

}