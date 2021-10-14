<?php include __DIR__ . '/../../header.php'; ?>
<div class="container mx-1">
    <h2 style="text-align: center">Edit comment</h2>
    <?php if (!empty($error)) : ?>
        <div style="background-color: red;padding: 5px;margin: 15px;color: white"><?= $error ?></div>
    <?php endif; ?>
    <div class="row" style="border: solid 2px black">
        <div class="col-sm-2">
            <b>Comment:</b>
        </div>
        <div class="col-sm-10" style="color: dimgray; border-left: solid 2px black">
            <?= $comment->getText() ?>
        </div>
    </div>
    <form action="/ex4/ex5/another_MVC/www/articles/<?=$comment->getArticleId()?>/comments/<?=$comment->getId()?>/edit" method="POST">
        <label for="text">New text:</label><br>
        <textarea class="form-control" name="text" id="text" rows="2" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
        <button class="btn btn-success" type="submit">Update</button>
    </form>
</div>
<?php include __DIR__ . '/../../footer.php'; ?>
