<?php

namespace App\Managers;

use App\Entities\Post;
use App\Factories\MySQLFactory;
use App\Managers\CommentManager;
use Generator;

class PostManager extends BaseManager
{
    /**
     * @return ?Generator
     */
    public function getPosts(): ?Generator
    {
        $query = $this->pdo->query('SELECT * FROM posts');        
        $commentManager = new CommentManager();
        $userManager = new UserManagers(new MySQLFactory());

        while($post = $query->fetch(\PDO::FETCH_ASSOC)) {
            $post['comments'] = $commentManager->getCommentsByPost($post['id']);
            $post['author'] = $userManager->getUser(intval($post['author']));
            yield new Post($post);
        }
    }

    public function getPost(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $query->bindValue("id", $id, \PDO::PARAM_INT);
        $query->execute();
        $post = $query->fetch(\PDO::FETCH_ASSOC);
        $commentManager = new CommentManager();
        $userManager = new UserManagers(new MySQLFactory());

        if ($post) {
            $post['comments'] = $commentManager->getCommentsByPost($id);
            $post['author'] = $userManager->getUser(intval($post['author']));
            return new Post($post);
        }

        return null;
    }

    public function createPost(Post $post) : void
    {
        $query = $this->pdo->prepare("INSERT INTO posts (author, title, content, unix) VALUES (:author, :title, :content, :unix)");
        $query->bindValue("author", $post->getAuthor()->getId(), \PDO::PARAM_STR);
        $query->bindValue("title", $post->getTitle(), \PDO::PARAM_STR);
        $query->bindValue("content",$post->getContent(), \PDO::PARAM_STR);
        $query->bindValue("unix",$post->getUnix(), \PDO::PARAM_STR);
        $query->execute();
    }

    public function updatePost(Post $post)
    {
        $query = $this->pdo->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
        $query->bindValue("title", $post->getTitle(), \PDO::PARAM_STR);
        $query->bindValue("content",$post->getContent(), \PDO::PARAM_STR);
        $query->execute();
    }

    public function deletePost(int $id) : void 
    {
        $query = $this->pdo->prepare("DELETE FROM posts WHERE id = :id");
        $query->bindValue("id", $id, \PDO::PARAM_STR);
        $query->execute();
    }
}