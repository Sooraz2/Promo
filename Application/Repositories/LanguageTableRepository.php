<?php

namespace Repositories;

use System\Repositories\Repo;

class LanguageTableRepository extends Repo {
    private $table;


    function __construct()
    {
        $this->table = "language";

        parent::__construct($this->table, "WebInterface\\Models\\Language");

    }
} 