<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Factories\MySQLFactory;
use App\Managers\PostManager;
use App\Managers\TokenManager;
use App\Entities\Post;

class PostController extends BaseController
{
    public function index()
    {
        $manager = new PostManager(new MySQLFactory());
        $posts = $manager->getPosts();
        $this->render('posts', ['posts' => $posts]);
    }

    public function post($id)
    {
        $manager = new PostManager(new MySQLFactory());
        $post = $manager->getPost($id);
        $this->render('post', ['post' => $post]);
    }
    
    public function createPost(){
        $title = $_POST['title'];
        $content = $_POST['htmlcontent'];
        $PostManager=new PostManager(new MySQLFactory());
        $TokenManager=new TokenManager();
        $user=$TokenManager->getUserFromToken($_COOKIE["token"]);
        $Post=new Post([
            'author' => $user,
            'title' => $title,
            'content' => $content,
            "comments" => [],
            'unix'=> date_create(
                date('l H:i:s'),
                new \DateTimeZone('Europe/Paris'))->getTimestamp()
            ]);
        $PostManager->createPost($Post);
        exit(header("location: /"));
        
    }
    public function CreatePostForm()
   {
      $this->render('CreatePost', []);
   }
}
