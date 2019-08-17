<?php

namespace App\Library;

class Database
{
    protected $dbPath;

    public function __construct()
    {
        $this->dbPath = STORAGE_DIR . 'database.db';
    }

    public function db()
    {
        return new SQLite3($this->dbPath);
    }
}