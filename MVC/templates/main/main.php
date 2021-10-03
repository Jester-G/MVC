<?php include __DIR__ . '/../header.php'; ?>
<div class="container mx-1">
<?php foreach ($articles as $article): ?>
    <h2><a href="/ex4/ex5/another_MVC/www/articles/<?= $article->getId() ?>"><?= $article->getName() ?></a></h2>
    <p><?= $article->getText() ?></p>
    <hr>
<?php endforeach; ?>
</div>
<?php include __DIR__ . '/../footer.php';
