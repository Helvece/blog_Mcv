<?php

namespace App\Managers;

use App\Entities\Comment;
use App\Factories\MySQLFactory;
use App\Managers\BaseManager;
use Generator;

class CommentManager extends BaseManager
{
    

    public function __construct()
    {
        parent::__construct(new MySQLFactory());
    }

    /**
     * @return Comment[]
     */
    public function getCommentsByPost(int $post_id): array
    {

        $comments = [];
        $generator = $this->getCommentsGenerator($post_id);
        if( $generator !== null){
            foreach( $generator  as $comment){
                $comments[$comment->getId()] = $comment;
            }
        }
        return $comments;
    }

    public function getCommentsGenerator(int $post_id): ?Generator
    {
        $query = $this->pdo->query('SELECT * FROM comments WHERE post_id = ' . "'$post_id'");
        $query->execute();
        $userManager = new UserManagers(new MySQLFactory());
        while($comment = $query->fetch(\PDO::FETCH_ASSOC)) {
            $comments = $this->getSubCommants(json_decode($comment['comments'] ?? "[]"), $subComments);
            $comment['comments'] = $subComments;
            $comment['author'] = $userManager->getUser(intval($comment['author_id']));
            unset($comment['author_id']);
            yield new Comment($comment);
        }
    }
    
    public function getSubCommants(array $comments_id, ?array &$subComments = null): void{
        if($subComments === null){
            $subComments = [];
        }
        foreach($comments_id as $comment_id){
            $query = $this->pdo->query('SELECT * FROM comments WHERE id =' . "'$comment_id'");
            $query->execute();
            $userManager = new UserManagers(new MySQLFactory());
            while($comment = $query->fetch(\PDO::FETCH_ASSOC)) {
                $comments = $this->getSubCommants(json_decode($comment['comments'] ?? "[]"), $subComment);
                $comment['comments'] = $subComment;
                $comment['author'] = $userManager->getUser(intval($comment['author_id']));
                unset($comment['author_id']);
                $subComments[$comment['id']] = new Comment($comment);
            }
        }

    }


    public function getComment(int $id)
    {
        $query = $this->pdo->prepare("SELECT id FROM comments WHERE `id` = :id");
        $query->bindValue(":id", $id, \PDO::PARAM_INT);
        $query->execute();
    
        return ($comment = $query->fetch(\PDO::FETCH_ASSOC)) ? new Comment($comment) : null;
    }

    public function deleteComment(int $id) : void 
    {
        $query = $this->pdo->prepare("DELETE FROM comments WHERE id = :id");
        $query->bindValue("id", $id, \PDO::PARAM_INT);
        $query->execute();
    }

    public function createComment(Comment $comment) : void
    {
        $query = $this->pdo->prepare("INSERT INTO comments (post_id, content, author_id, comments) VALUES (:post_id, :content, :author_id, :comments)");
        $query->bindValue("post_id", $comment->getPostId(), \PDO::PARAM_STR);
        $query->bindValue("content",$comment->getContent(), \PDO::PARAM_STR);
        $query->bindValue("author_id",$comment->getAuthor()->getId(), \PDO::PARAM_STR);
        $query->bindValue("comments", json_encode(array_keys($comment->getComments())), \PDO::PARAM_STR);
        $query->execute();
    }

    public function updateComment(Comment $comment)
    {
        $query = $this->pdo->prepare("UPDATE comments SET content = :content, comments = :comments WHERE id = :id");
        $query->bindValue("id", $comment->getId(), \PDO::PARAM_INT);
        $query->bindValue("content",$comment->getContent(), \PDO::PARAM_STR);
        $query->bindValue("content",json_encode(array_keys($comment->getComments())), \PDO::PARAM_STR);
        $query->execute();
    }
}