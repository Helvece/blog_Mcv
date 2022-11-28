<?php

namespace App\Managers;

use App\Interfaces\IDatabase;

abstract class BaseManager
{
    protected \PDO $pdo;

    public function __construct (IDatabase $factory)
    {
        $this->pdo = $factory->connect();
    }
}