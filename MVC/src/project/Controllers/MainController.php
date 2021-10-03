<?php

namespace project\Controllers;

use project\Models\Articles\Article;

class MainController extends AbstractController
{
    /*private $view;
    private $user;
    //private $db;

    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
        //$this->db = new DB();
    }*/

    public function main()
    {
        /*$articles = [
            ['name' => 'Article #1', 'text' => 'text of article #1'],
            ['name' => 'Article #2', 'text' => 'text of article #2'],
        ];*/
        $articles = Article::findAll();
        //var_dump($articles);
        $this->view->renderHtml('main/main.php', ['articles' => $articles]);
    }

    public function logout()
    {
        setcookie('token', '', -1, '/ex4/ex5/another_MVC/www', '', false, true);
        header('Location: /ex4/ex5/another_MVC/www/');
        exit();
    }

    public function error() {
        throw new \project\Exceptions\NotFoundException();
    }

    public function sayHello(string $name)
    {
        $this->view->renderHtml('main/hello.php', ['name' => $name, 'title' => 'Hello']);
    }

    public function sayBye(string $name)
    {
        $this->view->renderHtml('main/bye.php', ['name' => $name, 'title' => 'Bye']);
    }
}