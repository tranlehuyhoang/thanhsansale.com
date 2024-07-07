<?php

namespace App\Core;

use PDO;

class Model
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
