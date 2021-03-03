<?php require VIEW_DIR . '/layout/base/header.php'; ?>
<main class="row justify-content-center">
    <div class="col-sm-8 p-0 article-container">

        <?php foreach($data['articles'] as $article) : ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="card article">
                    <img src="<?= $article['img'] ?>" alt="Статья" class="card-img-top article__img">
                    <div class="card-body p-0 article__body">
                        <h5 class="card-title article__header"><?= $article['name'] ?></h5>
                        <p class="article__date"><?= $article['date'] ?></p>
                        <p class="card-text article__text"><?= $article['description'] ?></p>
                        <a href="<?= '/article?id=' . $article['id'] ?>" class="article__button blog-button">Продолжить</a>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

        <div class="blog-pagination">
            <ul class="pagination pagination-sm justify-content-center blog-pagination__ul">
                <?php if ($data['page'] != 1) : ?>
                    <li class="page-item blog-pagination__li">
                        <a class="page-link article-pagination-link" href="<?= '/' . '?page=' . ($data['page'] - 1)  ?>">Назад</a>
                    </li>
                <?php endif; ?>
                <?php for($i = 1; $i <= $data['maxPage']; $i++) : ?>
                    <?php if ($i != $data['page']) : ?>
                        <li class="page-item blog-pagination__li">
                            <a class="page-link article-pagination-link" href="<?= '/' . '?page=' . $i  ?>"><?= $i ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($data['page'] != $data['maxPage']) : ?>
                    <li class="page-item blog-pagination__li">
                        <a class="page-link article-pagination-link" href="<?= '/' . '?page=' . ($data['page'] + 1)  ?>">Вперед</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</main>
<div class="row subscribe-container">
    <?php if (isset($_SESSION['isUserAuthorized']) && $_SESSION['isUserAuthorized']) : ?>
        <?php if ($data['user']['subscribe']) :?>
            <div class="subscribe-content">
                <h5 class="subscribe-header">Вы подписаны на нашу рассылку</h5>
            </div>
        <?php else : ?>
            <div class="subscribe-content">
                <h5 class="subscribe-header">Подпишитесь на нашу рассылку</h5>
                <p class="subscribe-email"><?= $data['user']['email'] ?></p>
                <button class="blog-button subscribe-user-button">Подписаться</button>
            </div>
        <?php endif; ?>
    <?php else : ?>
        <div class="subscribe-content">
            <h5 class="subscribe-header">Подпишитесь на нашу рассылку</h5>
            <input type="text" class="subscribe-email-input form-control">
            <p class="subscribe-answer d-none"></p>
            <button class="blog-button subscribe-guest-button">Подписаться</button>
        </div>
    <?php endif; ?>
</div>
<?php require VIEW_DIR . '/layout/base/footer.php'; ?>
