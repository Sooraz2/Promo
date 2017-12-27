<?php

namespace Language;

interface LanguageInterface
    {
        public function getLanguageCookie();

        public function setLanguage();

        public function setMessage();
    }