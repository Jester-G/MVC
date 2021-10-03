<?php include __DIR__ . '/../header.php'; ?>
<div class="container">
    <h1><?= $article->getName() ?></h1>
    <p><?= $article->getText() ?></p>

    <p style="text-align: right">
        Author: <?= $article->getAuthor()->getName() ?? 'INCOGNITO' ?>
        <br>
        <?php if ($isArticleByUser)  : ?>
            <a href="/ex4/ex5/another_MVC/www/articles/<?= $article->getId() ?>/edit">
                <button class="btn btn-success" type="button" value="Edit">Edit</button>
            </a>
        <?php endif; ?>

    </p>
    <?php if (!empty($error)) : ?>
        <div style="background-color: red;padding: 5px;margin: 15px;color: white"><?= $error ?></div>
    <?php endif; ?>
    <?php if (!empty($user)) : ?>
        <form action="/ex4/ex5/another_MVC/www/articles/<?= $article->getId() ?>/comments" method="POST">
            <label for="text">Add comment:</label><br>
            <textarea class="form-control" name="text" id="text" rows="2" cols="80"><?= $_POST['text'] ?? '' ?></textarea>
            <button class="btn btn-success" type="submit">Add</button>
        </form>
    <?php endif; ?>
    <p><b>Comments:</b></p>
    <div class="container" style="font-size: medium">
    <?php foreach ($comments as $comment) { $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);?>
        <div class="row" style="border: solid 2px <?=$color?>">
            <div class="col-sm-2">
                <b><?= $comment->getAuthor()->getName(); ?></b>
                <br>
                <span style="font-size: smaller;"><?= $comment->getAuthor()->getRole(); ?></span>

            </div>
                <div class="col-sm-10" style="color: dimgray; border-left: solid 2px <?=$color?>;"><?= $comment->getPublishedAt()?>
                    <br>
                    <span style="color:black; font-size: large">
                    <?= $comment->getText(); ?>
                    </span>
                </div>
        </div>
        <br>
        <!--<p class="comments" style="border-color:<?/*=$color*/?>"><b><?/*= $comment->getAuthor()->getName(); echo '</b> написал в ' . $comment->getPublishedAt()*/?>
            <br>
        <?/*= $comment->getText(); */?>
        </p>-->
    <?php } ?>
    </div>
</div>
<?php include __DIR__ . '/../footer.php';

//($article->getAuthor()->getName() == $user->getName()))
//(!empty($user) && ($article->getAuthor()->getName() === $user->getName()))