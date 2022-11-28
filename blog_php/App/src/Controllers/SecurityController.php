<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Entities\User;
use App\Managers\UserManagers;
use App\Factories\MySQLFactory;
use App\Helper\PasswordHelper;
use App\Managers\TokenManager;


class SecurityController extends BaseController
{
   public function login(){

    $Username = $_POST['username'];
    $Pwd = $_POST['password'];

    $UserManager = new UserManagers(new MySQLFactory());
    $user = $UserManager->getUserName($Username);
    if (!$user){
      exit(header("location: /login?error=notfound"));
      return;
    }
    $PwdHelper= new PasswordHelper();
    if($PwdHelper->verifyPassword($Pwd,$user->getPwHash())){
      $tokenManager=new TokenManager;
      $token=$tokenManager->create();
      $tokenManager->storeTokenForUser($token,$user);
      header("Location: /");
      exit;
   }
   header("Location: /login?error=notfound");
      exit;
}
   public function register(){

    $Username = $_POST['username'];
    $Pwd = $_POST['password'];
    $PwdConf = $_POST['passwordConf'];
    if ($Pwd !== $PwdConf){
      header("location: /register?error=notmatch");
      exit;
    }
    $mail = $_POST['mail'];

    $user = new User([
      'username' => $Username,
      'mail' => $mail,
      'pw_hash' => $Pwd
    ]);
    
    $UserManager = new UserManagers(new MySQLFactory());
    $UserManager->createUser($user);

    $user = $UserManager->getUserName($user->getUsername());
    $tokenManager = new TokenManager();
    $token = $tokenManager->create();
    $tokenManager->storeTokenForUser($token, $user);

    exit;
    
   }

   public function loginRender()
   {
      $this->render('login', []);
   }

   public function registerRender()
   {
       $this->render('Register', []);
   }
}