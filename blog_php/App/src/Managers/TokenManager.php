<?php

namespace App\Managers;
use App\Entities\User;
use App\Factories\MySQLFactory;
use App\Helper\TokenHelper;

class TokenManager extends BaseManager
{

    public function __construct()
    {
        parent::__construct(new MySQLFactory());
    }

    public function checkToken(string $token): bool
    {
        $query = $this->pdo->prepare("SELECT * FROM tokens where token = :token");
        $query->bindValue("token", $token);
        return (bool)$query->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function getUserFromToken(string $token) : ?User 
    
    {
        $query = $this->pdo->prepare("SELECT id FROM tokens WHERE token = '$token'");
        $query->execute();
        $user=$query->fetch(\PDO::FETCH_ASSOC);
        var_dump($user);
        $userManager = new UserManagers(new MySQLFactory());
        return $userManager->getUser($user['id']);
    }

    public function storeTokenForUser(string $token, User $user) 
    {
        $query = $this->pdo->prepare("INSERT tokens (token, id) VALUES (:token, :user_id)");
        $query->bindValue("token",$token);
        $query->bindValue("user_id",$user->getId());
        $query->execute();
    
    }

    public function deleteToken(string $token) : void
    {
        $query = $this->pdo->prepare("DELETE FROM tokens WHERE token = :token");
        $query->bindValue("token",$token);  
        $query->execute();
      
    }

    public function create() : string
    {
        $tokenHelper = new TokenHelper();
        $token = $_COOKIE['token'] ?? null;
        $token ??= $tokenHelper->createToken();
        //set token to cookies
        if(!isset($_COOKIE['token'])){
            setcookie('token', $token);
        }
        return $token;
    }

    public function getUserFromCookies() : ?User {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            return $this->getUserFromToken($token);
        }
    }

    
}