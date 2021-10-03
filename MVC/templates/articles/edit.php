<?php include __DIR__ . '/../header.php'; ?>
<div class="container mx-1">
    <h2 style="text-align: center">Edit article</h2>
    <?php if (!empty($error)) : ?>
        <div style="background-color: red;padding: 5px;margin: 15px;color: white"><?= $error ?></div>
    <?php endif; ?>
    <form action="/ex4/ex5/another_MVC/www/articles/<?= $article->getId() ?>/edit" method="POST">
        <label for="name">Article title</label><br>
        <input type="text" class="form-control" name="name" id="name" value="<?= $_POST['name'] ?? $article->getName() ?>" size="50">
        <br>
        <label for="text">Article text</label><br>
        <textarea class="form-control" name="text" id="text" rows="10" cols="80"><?= $_POST['text'] ?? $article->getText() ?></textarea>
        <br>
        <button class="btn btn-success" type="submit">Update</button>
    </form>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
