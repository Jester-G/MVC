<?php

namespace project\Controllers;

use project\Models\Users\UsersAuthService;
use project\View\View;

abstract class AbstractController
{
    protected $view;

    protected $user;

    public function __construct()
    {
        $this->user = UsersAuthService::getUserByToken();
        $this->view = new View(__DIR__ . '/../../../templates');
        $this->view->setVar('user', $this->user);
    }
}