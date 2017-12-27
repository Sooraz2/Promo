<?php

namespace Libraries\Validation;

class ConfirmationMessage extends ConfirmationLanguage
{

    public function __construct(){
        parent::__construct();
    }

    public $MessageType;

    public $Message;
}
