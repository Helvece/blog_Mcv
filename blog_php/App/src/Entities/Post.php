<?php

namespace App\Entities;
use App\Entities\BaseEntity;

class Post extends BaseEntity
{
    public int $id;
    public User $author;
    public string $title;
    public string $content;
    public int $unix;
    /**  @var Comment[] */
    public array $comments = [];

    // Getters
    public function getId(): int { return $this->id; }
    public function getAuthor(): User { return $this->author; }
    public function getTitle(): string { return $this->Title; }
    public function getContent(): string { return $this->content; }
    public function getUnix(): int { return $this->unix; }
    public function getComments(): array { return $this->comments; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setAuthor(User $author): void { $this->author = $author; }
    public function setTitle(string $Title): void { $this->Title = $Title; }
    public function setContent(string $content): void { $this->content = $content; }
    public function setUnix(int $unix): void { $this->unix = $unix; }
    public function setComments(array $comments): void { $this->comments = $comments; }
    public function addComment(Comment $comment): void {
        $this->comments[$comment->getId()] = $comment;
    }
    public function removeComment(Comment $comment): void{
        if(!array_key_exists($this->getId(), $this->comments)){
            return;
        }
        unset($this->comments[$comment->getId()]);
    }
}