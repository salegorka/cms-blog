<?php require VIEW_DIR . '/layout/base/header.php'; ?>
    <h1>Книги в БД</h1>
<p>
    <?= $data ?>
</p>
<p>
    <?php foreach($data as $book) : ?>
        Автор: <?= $book['author'] ?> Название: <?= $book['name'] ?>
        <br/>
    <?php endforeach; ?>
</p>
<?php require VIEW_DIR . '/layout/base/footer.php'; ?>