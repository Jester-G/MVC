<?php

namespace project\Controllers;

use project\Exceptions\ForbiddenException;
use project\Exceptions\InvalidArgumentException;
use project\Exceptions\UnauthorizedException;
use project\Exceptions\UserNotFoundException;
use project\Models\Articles\Article;
use project\Models\Comments\Comments;

class CommentsController extends AbstractController
{
    public function addComment(int $articleId) : void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            $main = new MainController();
            $main->error();
        }

        if ($this->user === null) {
            throw new UserNotFoundException('User not found btw.');
        }

        if (!empty($_POST)) {
            try {
                $_POST['articleId'] = $articleId;
                $comment = Comments::createComment($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $article = new ArticlesController();
                $article->view->setVar('error', $e->getMessage());
                $article->view($articleId);
                return;
            }

            header('Location: /ex4/ex5/another_MVC/www/articles/' . $article->getId() . '#comment' . $comment->getId(), true, 302);
            exit();
        }
    }

    public function deleteComment(int $articleId, int $commentId) : void
    {
        $comment = Comments::getById($commentId);

        if ($comment === null) {
            $main = new MainController();
            $main->error();
        }

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!($comment->getAuthor()->getName() === ($this->user->getName()) || ($this->user->isAdmin()))) {
            throw new ForbiddenException('Only a user with admin rights or owner can delete comments.');
        }

        $comment->delete();

        $article = new ArticlesController();
        $article->view->setVar('error', 'Комментарий успешно удален!');
        $article->view($articleId);

        //var_dump($comment);
    }

    public function editComment(int $articleId, int $commentId) : void
    {
        $comment = Comments::getById($commentId);

        if ($comment === null) {
            $main = new MainController();
            $main->error();
        }
        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!($comment->getAuthor()->getName() === ($this->user->getName()) || ($this->user->isAdmin()))) {
            throw new ForbiddenException('Only a user with admin rights or owner can edit comments.');
        }

        if (!empty($_POST)) {
            try {
                $comment->updateComment($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/comments/edit.php', ['error' => $e->getMessage(), 'comment' => $comment] );
                return;
            }

            header('Location: /ex4/ex5/another_MVC/www/articles/' . $articleId . '#comment' . $comment->getId(), true, 302);
            exit();
        }
        $this->view->renderHtml('articles/comments/edit.php', ['comment' => $comment]);

        //var_dump($comment);
    }

}