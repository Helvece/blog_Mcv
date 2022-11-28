<?php

namespace App\Managers;

use App\Entities\User;
use App\Helper\PasswordHelper;
use Generator;

class UserManagers extends BaseManager
{

    public function getUsers(): ?Generator
    {
        
        $query = $this->pdo->query("SELECT * FROM users");

        while($data = $query->fetch(\PDO::FETCH_ASSOC)){
            yield new User($data);
        }

    }
    

    public function getUser(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindValue("id", $id, \PDO::PARAM_INT);
        $query->execute();
        $data = $query->fetch(\PDO::FETCH_ASSOC);

        if ($data) {
            return new User($data);
        }

        return null;
    }

    public function getUserName(string $username): ?User
    {
        $query = $this->pdo->prepare("SELECT username, pw_hash, id FROM users WHERE username = :username");
        $query->bindValue("username", $username, \PDO::PARAM_STR);
        $query->execute();
        $data = $query->fetch(\PDO::FETCH_ASSOC);
        if ($data) {
            return new User($data);
        }

        return null;
    }

    public function createUser(User $user)
    {
        $query = $this->pdo->prepare("INSERT INTO users (username, username_safe, mail, pw_hash, rank) VALUES (:username, :username_safe, :mail, :pw_hash, 1)");
        $pwHelper = new PasswordHelper();
        $pwHash = $pwHelper->hashPassword($user->getPwHash());
        $query->execute([
            ":username" => $user->getUsername(),
            ":username_safe" => strtolower($user->getUsername()),
            ":mail" => $user->getMail(),
            ":pw_hash" => $pwHash
        ]);

    }

    public function updateUser($user)
    {
        $query = $this->pdo->prepare("UPDATE users SET username = :username, mail = :mail, pw_hash = :pw_hash WHERE id = :id");
        $query->bindValue("pw_hash", $user->getPwHash(), \PDO::PARAM_STR);
        $query->bindValue("username", $user->getUsername(), \PDO::PARAM_STR);
        $query->bindValue("mail",$user->getMail(), \PDO::PARAM_STR);
        $query->execute();
    }

    public function deleteUser($id)
    {
        $query = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $query->bindValue("id", $id, \PDO::PARAM_INT);
        $query->execute();
    }

    public function login($email, $password)
    {
        $query = $this->pdo->prepare("SELECT id FROM users WHERE email = :email; pw_hash = :pw_hash");
        $query->execute();
    
    }

    public function Register(User $user)
    {
        $query = $this->pdo->prepare("INSERT INTO User (password, username), VALUES (:password, :username)");
        $query->bindValue("password", $user->getPwHash(), \PDO::PARAM_STR);
        $query->bindValue("username", $user->getUsername(), \PDO::PARAM_STR);
        $query->execute();
    }
}