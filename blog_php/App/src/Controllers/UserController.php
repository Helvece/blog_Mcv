<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Factories\MySQLFactory;
use App\Managers\UserManagers;
use App\Managers\TokenManager;
class UserController extends BaseController
{
    public function ShowUsers()
    {
        $manager = new UserManagers(new MySQLFactory());
        $Users = $manager->getUsers();
        $this->render('showUser', ['Users' => $Users]);
    }

    public function isLogged() : bool {
        if (isset($_COOKIE['token'])) {
            $token = $_COOKIE['token'];
            $tokenManager=new TokenManager;
            $user = $tokenManager->getUserFromToken($token);
            if ($user) {
                return true;
            }
        }
        return false;
    }
}