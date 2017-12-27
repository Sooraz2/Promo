<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2/9/2015
 * Time: 11:33 AM
 */


namespace Language;

use Infrastructure\CookieVariable;
use Infrastructure\DefaultLanguages;
use Language\English\English;

class Language implements LanguageInterface
{

    private $language;
    private $message;
    public $languageClass;

    public function __construct()
    {
        $this->getLanguageCookie();
        $this->setLanguage();
        $this->setMessage();
    }

    public function getLanguageCookie()
    {
        $this->language = isset($_COOKIE[CookieVariable::$BalancePlusLanguage]) ? $_COOKIE[CookieVariable::$BalancePlusLanguage] : DefaultLanguages::$DefaultLanguage;

        $this->message = "Message";
        if ($this->language == "French") {
            $this->message = "Language\\{$this->language}\\{$this->message}";
            $this->language = "Language\\{$this->language}\\{$this->language}";
        } else if ($this->language == "Russian") {
            $this->message = "Language\\{$this->language}\\{$this->message}";
            $this->language = "Language\\{$this->language}\\{$this->language}";
        } else if ($this->language == "Georgian") {
            $this->message = "Language\\{$this->language}\\{$this->message}";
            $this->language = "Language\\{$this->language}\\{$this->language}";
        } else {
            $this->message = "Language\\English\\Message";
            $this->language = "Language\\English\\English";
        }
    }

    public function setLanguage()
    {
        $this->languageClass = new $this->language();
    }

    public function setMessage()
    {
        if (class_exists($this->message))
            $this->messageClass = new $this->message();

    }
}