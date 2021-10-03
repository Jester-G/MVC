<?php
return [
    '~^$~' => [\project\Controllers\MainController::class, 'main'],
    '~^articles/add$~' => [\project\Controllers\ArticlesController::class, 'add2'],
    '~^articles/(\d+)/add$~' => [\project\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)$~' => [\project\Controllers\ArticlesController::class, 'view'],
    '~^articles/(\d+)/edit$~' => [\project\Controllers\ArticlesController::class, 'edit'],
    '~^articles/(\d+)/comments$~' => [\project\Controllers\ArticlesController::class, 'addComment'],
    '~^articles/(\d+)/delete$~' => [\project\Controllers\ArticlesController::class, 'delete'],
    '~^users/register~' => [\project\Controllers\UserController::class, 'signUp'],
    '~^users/login$~' => [\project\Controllers\UserController::class, 'login'],
    '~^users/(\d+)/activate/(.+)$~' => [\project\Controllers\UserController::class, 'activate'],
    '~^logout$~' => [\project\Controllers\MainController::class, 'logout'],
    '~^hello/(.*)$~' => [\project\Controllers\MainController::class, 'sayHello'],
    '~^bye/(.*)$~' => [\project\Controllers\MainController::class, 'sayBye'],
    //'~^.*$~' => [\project\Controllers\MainController::class, 'error'],
];