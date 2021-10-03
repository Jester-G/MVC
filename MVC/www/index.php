<?php

//require_once "C:/xampp/php/vendor/autoload.php";

try {
    spl_autoload_register(function (string $className) {
        require_once __DIR__ . '/../src/' . $className . '.php';
    });

    $route = $_GET['route'] ?? '';
    $routes = require __DIR__ . '/../src/routes.php';
//var_dump($routes);

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction) {
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) {
        //var_dump($controllerAndAction);
        //var_dump($matches);
        throw new \project\Exceptions\NotFoundException();
    }

    unset($matches[0]);
//var_dump($controllerAndAction);
//var_dump($matches);
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
} catch (\project\Exceptions\DbException $e) {
    $view = new \project\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\project\Exceptions\NotFoundException $e) {
    $view = new \project\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('404.php', ['error' => $e->getMessage()], 404);
} catch (\project\Exceptions\UserNotFoundException $e) {
    $view = new \project\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('500.php', ['error' => $e->getMessage()], 500);
} catch (\project\Exceptions\UnauthorizedException $e) {
    $view = new \project\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('401.php', ['error' => $e->getMessage()], 401);
} catch(\project\Exceptions\ForbiddenException $e) {
    $view = new \project\View\View(__DIR__ . '/../templates/errors');
    $view->renderHtml('403.php', ['error' => $e->getMessage()], 403);
}


/*
$pattern = '~^hello/(.*)$~';
preg_match($pattern, $route, $matches);

//var_dump($matches);

if (!empty($matches)) {
    $controller = new \project\Controllers\MainController();
    $controller->sayHello($matches[1]);
    return;
}

$pattern = '~^$~';
preg_match($pattern, $route, $matches);

if (!empty($matches)) {
    $controller = new \project\Controllers\MainController();
    $controller->main();
    return;
}

echo 'Страница не найдена.';*/