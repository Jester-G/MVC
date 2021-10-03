<?php
namespace project\Models\Comments;
use project\Exceptions\InvalidArgumentException;
use project\Models\ActiveRecordEntity;
use project\Models\Users\User;

class Comments extends ActiveRecordEntity
{
    protected $authorId;
    protected $articleId;
    protected $text;
    protected $publishedAt;
    /**
     * @return int
     */
    public function getAuthorId() : int
    {
        return $this->authorId;
    }
    public function getAuthor() : User
    {
        return User::getById($this->authorId);
    }
    /**
     * @return integer
     */
    public function getArticleId() : int
    {
        return $this->articleId;
    }
    /**
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }
    /**
     * @return \DateTime
     */
    public function getPublishedAt() : string
    {
        $date = new \DateTime($this->publishedAt);
        return $date->format('H:i M d,Y');
    }
    /**
     * @param int $authorId
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }
    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }
    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    protected static function getTableName(): string
    {
        return 'comments';
    }

    public static function createFromArray(array $fields, User $user)
    {
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария.');
        }

    }

}