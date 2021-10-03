<?php

namespace project\Models\Articles;

use project\Exceptions\InvalidArgumentException;
use project\Models\ActiveRecordEntity;
use project\Models\Users\User;

class Article extends ActiveRecordEntity
{
    protected $name;
    protected $text;
    protected $authorId;
    protected $createdAt;

    public function getName() : string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getText() : string
    {
        return $this->text;
    }

    public function setText($text): void
    {
        $this->text = $text;
    }

    public function setAuthorId(User $author): void
    {
        $this->authorId = $author->getId();
    }

    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    protected static function getTableName() : string
    {
        return 'articles';
    }

    public function getAuthor() : User
    {
        return User::getById($this->authorId);
    }

    public function getCreatedAt() : string
    {
        return $this->createdAt;
    }

    public static function createFromArray(array $fields, User $author) : Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи.');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи.');
        }

        $article = new Article();

        $article->setAuthorId($author);
        $article->setName($fields['name']);
        $article->setText($fields['text']);

        $article->save();

        return $article;
    }

    public function updateFromArray(array $fields) : Article
    {
        if (empty($fields['name'])) {
            throw new InvalidArgumentException('Не передано название статьи.');
        }

        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст статьи.');
        }

        $this->setName($fields['name']);
        $this->setText($fields['text']);

        $this->save();

        return $this;
    }


}