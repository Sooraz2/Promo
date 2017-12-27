<?php

namespace Libraries\Validation;

use Language\English\English;

class ConfirmationLanguage{

    /**
     * @var $language English
     * **/

    public $language;

    public function __construct()
    {
        global $langConfig;
        $this->language = $langConfig->languageClass;
    }

} 