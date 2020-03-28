<?php


namespace App\Services\Database;


use Jajo\JSONDB;

class Database extends JSONDB implements DatabaseInterface
{
    public function __construct($dir)
    {
        parent::__construct($dir);
    }
}
