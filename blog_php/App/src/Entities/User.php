<?php

namespace App\Entities;
use App\Interfaces\IUser;
use App\Entities\BaseEntity;

class User extends BaseEntity implements IUser
{
    private int $id;
    private string $username;
    private string $username_safe;
    private string $mail;
    private string $Pw_hash;
    private string $Token;
    private int $rank;

    // Getters
    public function getId() : int { return $this->id; }
    public function getUsername() : string { return $this->username; }
    public function getUsernameSafe() : string { return $this->username_safe; }
    public function getMail() : string { return $this->mail; }
    public function getPwHash() : string { return $this->Pw_hash; }
    public function getToken() : string { return $this->Token; }
    public function getRank() : int { return $this->rank; }

    // Setters
    public function setId(int $id) : void { $this->id = $id; }
    public function setUsername(string $username) : void { $this->username = $username; }
    public function setUsernameSafe(string $username_safe) : void { $this->username_safe = $username_safe; }
    public function setMail(string $mail) : void { $this->mail = $mail; }
    public function setPw_hash(string $Pw_hash) : void { $this->Pw_hash = $Pw_hash; }
    public function setToken(string $Token ): void { $this->Token = $Token; }
    public function setRank(int $rank) : void { $this->rank = $rank; }

}