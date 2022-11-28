<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Entities\Comment;
use App\Factories\MySQLFactory;
use App\Managers\PostManager;
use App\Managers\TokenManager;
use App\Entities\Post;

/*class CommentController extends BaseController
{
public function createComment(){
        $content = $_POST['htmlcontent'];
        $PostManager=new PostManager(new MySQLFactory());
        $TokenManager=new TokenManager();
        $user=$TokenManager->getUserFromToken($_COOKIE["token"]);
        $Comment=new Comment([
            'author' => $user,
            'post'=>
            'content' => $content,
            "comments" => [],
            'unix'=> date_create(
                date('l H:i:s'),
                new \DateTimeZone('Europe/Paris'))->getTimestamp()
            ]);
        $PostManager->createPost($Post);
        exit(header("location: /"));
        
    }
}*/