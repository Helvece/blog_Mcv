<?php

namespace App\Factories;
use App\Interfaces\IDatabase;

//make a Factory class using PDO using the interface
class MySQLFactory implements IDatabase
{
    private $pdo;
    private string $host;
    private string $user;
    private string $password;
    private string $database;

    public function __construct(string $host = "db", string $user = "root", string $password = "password", string $database = "blog")
                                /**/ 
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        
    }

    public function connect() : \PDO
    {
        $this->pdo = new \PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
        return $this->pdo;      
    }

     
}

    