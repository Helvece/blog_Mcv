<?php

namespace App\Interfaces;


interface IDatabase
{
    /**
     * Connect to the database
     * @return null
     */
    public function connect() : \PDO;
}