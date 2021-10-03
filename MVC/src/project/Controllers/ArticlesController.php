<?php

namespace project\Controllers;

use project\Exceptions\ForbiddenException;
use project\Exceptions\InvalidArgumentException;
use project\Exceptions\UnauthorizedException;
use project\Exceptions\UserNotFoundException;
use project\Models\Articles\Article;
use project\Models\Comments\Comments;
use project\Models\Users\User;

class ArticlesController extends AbstractController
{
       //private $db;

   /* public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
        //$this->db = new DB();
    }*/

    public function view(int $articleId) : void
    {
        //$result = $this->db->query("SELECT * FROM `articles` WHERE `id` = $articleId", [], Article::class);
        //[':id' => $articleId]);

        $article = Article::getById($articleId);
        $comments = Comments::findAllComments($articleId);
        //var_dump($comments);
        //var_dump($article);
        //return;
        //$result = $this->db->query("SELECT a.author_id, a.text, a.name, u.nickname FROM `articles` a JOIN `users` u ON a.author_id = u.id WHERE a.id = $articleId");
        //$reflector = new \ReflectionObject($article);
        //$properties = $reflector->getProperties();
        //$propertiesName = [];
        //foreach ($properties as $property) {
        //    $propertiesName[] = $property->getName();
        //}
        //var_dump($propertiesName);
        //return;

        if ($article === null) {
            $main = new MainController();
            $main->error();
            return;
        }

        $isArticleByUser = false;
        if ($this->user !== null && ($article->getAuthor()->getName() === ($this->user->getName()) || ($this->user->isAdmin()))) {
            $isArticleByUser = true;
        }
        /*$author = $this->db->query("SELECT `nickname` FROM `users` WHERE `id` = :id",
            [':id' => $article->getId()], User::class);*/

        //$author = User::getById($article->getAuthorId());
        //var_dump($result);
        $this->view->renderHtml('articles/view.php',
               ['article' => $article,
                'isArticleByUser' => $isArticleByUser,
                'comments' => $comments
               ]);
        //var_dump($article);
        //$this->view->renderHtml('main/main.php', ['articles' => $result]);
    }

    public function edit(int $articleId) : void
    {
        $article = Article::getById($articleId);

        if ($article === null) {
            $main = new MainController();
            $main->error();
        }

        if ($this->user === null) {
            throw new UserNotFoundException('User not found btw.');
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only a user with admin rights can edit articles.');
        }

        if (!empty($_POST)) {
            try {
                $article->updateFromArray($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/edit.php', ['error' => $e->getMessage(), 'article' => $article]);
                return;
            }

            header('Location: /ex4/ex5/another_MVC/www/articles/' . $article->getId(), true, 302);
            exit();
        }
/*        $article->setName('New Title');
        $article->setText('New Text');

        $article->save();*/
        $this->view->renderHtml('articles/edit.php', ['article' => $article]);
    }

    public function add(int $articleId) : void
    {
        $article = Article::getById($articleId);

        if ($article !== null) {
            $this->view($articleId);
            return;
        }
        $date = Date('Y-m-d H:i:s');
        $article = new Article();
        $article->setName('Add Title');
        $article->setText('Add Text');
        $article->setAuthorId(1);
        $article->setCreatedAt($date);

        $article->save();
    }

    public function add2() : void
    {
        /*$author = User::getById(2);

        $article = new Article();
        $article->setName('Add Title ex2');
        $article->setText('Add Text ex2');
        $article->setAuthorId($author);

        $article->save();

        var_dump($article);*/

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only a user with admin rights can add articles.');
        }

        if (!empty($_POST)) {
            try {
                $article = Article::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('articles/add.php', ['error' => $e->getMessage()]);
                return;
            }

            header('Location: /ex4/ex5/another_MVC/www/articles/' . $article->getId(), true, 302);
            exit();
        }

        $this->view->renderHtml('articles/add.php');
    }

    public function delete(int $articleId) : void
    {
        $article = Article::getById($articleId);

        if ($this->user === null) {
            throw new UnauthorizedException();
        }

        if (!$this->user->isAdmin()) {
            throw new ForbiddenException('Only a user with admin rights can delete articles.');
        }

        if ($article === null) {
            $main = new MainController();
            $main->error();
            return;
        }

        $article->delete();
        var_dump($article);
    }

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
                $comment = Comments::createFromArray($_POST, $this->user);
            } catch (InvalidArgumentException $e) {
                $this->view->setVar('error', $e->getMessage());
                $this->view($articleId);
                return;
            }
        }
    }
}