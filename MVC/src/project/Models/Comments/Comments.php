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
    protected $editedAt;
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
     * @return string
     */
    public function getPublishedAt() : string
    {
        $date = new \DateTime($this->publishedAt);
        return $date->format('H:i M d,Y');
    }
    /**
     * @return mixed
     */
    public function getEditedAt() : string
    {
        $editedDate = new \DateTime($this->editedAt);
        $pubDate = new \DateTime($this->publishedAt);

        if ($editedDate == ($pubDate)) {
            return '';
        }
        return $editedDate->format('H:i M d,Y');
    }
    /**
     * @param user $author
     */
    public function setAuthorId(user $author): void
    {
        $this->authorId = $author->getId();
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

    /**
     * @param mixed $editedAt
     */
    public function setEditedAt($editedAt): void
    {
        $this->editedAt = $editedAt;
    }

    protected static function getTableName(): string
    {
        return 'comments';
    }

    public static function createComment(array $fields, User $user) : Comments
    {
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария.');
        }

        $comment = new Comments();

        $comment->setArticleId($_POST['articleId']);
        $comment->setAuthorId($user);
        $comment->setText($fields['text']);

        $comment->save();

        return $comment;

    }

    public function updateComment(array $fields) : Comments
    {
        if (empty($fields['text'])) {
            throw new InvalidArgumentException('Не передан текст комментария.');
        }
        $date = new \DateTime();
        $date->add(new \DateInterval('PT1H'));

        $this->setText($fields['text']);
        $this->setEditedAt($date->format('Y-m-d H:i:s'));
        $this->save();

        return $this;
    }

}