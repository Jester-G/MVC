<?php include __DIR__ . '/../header.php'; ?>
<div class="container mx-1">
        <h2 style="text-align: center">Login:</h2>
        <?php if (!empty($error)) : ?>
            <div style="background-color: red;padding: 5px;margin: 15px;color: white"><?= $error ?></div>
        <?php endif; ?>
            <form action="/ex4/ex5/another_MVC/www/users/login" method="POST">
                <input type="text" class="form-control" name="email" id="email" placeholder="Введите E-mail" value="<?= $_POST['email'] ?? '' ?>">
                <br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" value="<?= $_POST['password'] ?? '' ?>">
                <br>
                <button class="btn btn-success" type="submit">Enter</button>
            </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?>