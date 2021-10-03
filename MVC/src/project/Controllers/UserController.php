<?php

namespace project\Controllers;

use project\Exceptions\InvalidArgumentException;
use project\Exceptions\NotFoundException;
use project\Exceptions\UserNotFoundException;
use project\Models\Users\UserActivationService;
use project\Models\Users\UsersAuthService;
use project\Models\Users\User;
use project\Services\EmailSender;

class UserController extends AbstractController
{
    /*private $view;

    public function __construct()
    {
        $this->view = new View(__DIR__ . '/../../../templates');
    }*/

    public function signUp()
    {
        if (!empty($_POST)) {
            try {
                $user = User::signUp($_POST);
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/signUp.php', ['error' => $e->getMessage()]);
                return;
            }

            if ($user instanceof User) {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Activation', 'userActivation.php',
                    [ 'userId' => $user->getId(),
                      'code' => $code
                    ]
                );

                $this->view->renderHtml('users/signUpSuccessful.php');
                return;
            }
        }
        $this->view->renderHtml('users/signUp.php');
    }

    public function activate(int $userId, string $activationCode) : void
    {
        $user = User::getById($userId);
        if ($user === null) {
            throw new UserNotFoundException('User not found btw.');
        }

        if ($user->isConfirmed()) {
            $this->view->renderHtml('mail/userAlreadyConfirmed.php');
            return;
        }

        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);

        if ($isCodeValid) {
            $user->activate();
            $this->view->renderHtml('mail/userActivationSuccessful.php', ['msg' => 'Почта успешно подтверждена.']);
            UserActivationService::deleteActiovationCode($user, $activationCode);
            return;
        } else {
            $this->view->renderHtml('mail/userActivationSuccessful.php', ['msg' => 'Неверный код активации.']);
            return;
        }

        throw new NotFoundException();
    }

    public function login()
    {
        if (!empty($_POST)) {
            try {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
                header('Location: /ex4/ex5/another_MVC/www/');
                exit();
            } catch (InvalidArgumentException $e) {
                $this->view->renderHtml('users/login.php', ['error' => $e->getMessage()]);
                return;
            }
        }

        $this->view->renderHtml('users/login.php');
    }
}