<?php

namespace App\Entities;

class Token extends BaseEntity
{
    private int $id;
    private string $token;

    public function setId(int $id): void {$this->id = $id;}
    public function setToken(string $token): void {$this->token= $token;}

    public function getId() : int { return $this->id; }
    public function getToken() : int { return $this->token; }
}