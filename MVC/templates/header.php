<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?= $title ?? 'Blog'?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/ex4/ex5/another_MVC/www/styles.css">
</head>
<body>

<table class="layout">
    <tr>
        <td colspan="2" class="header">

            Blog
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: right">
        <?= !empty($user) ? 'Hello, ' . $user->getName() .
            ' | <a href="/ex4/ex5/another_MVC/www/logout">Logout</a>': "Login to site" ?>
        </td>
    </tr>
    <tr>
        <td>
