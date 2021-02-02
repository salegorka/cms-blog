<?php require VIEW_DIR . '/layout/base/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-sm-8 p-0 article-container">

    <p><?=
        $data['article']['name'] ?>
    </p>
        <div>
            <?= $data['article']['text'] ?>
        </div>
<div class="comment-container">
    <?php if($_SESSION['isUserAuthorized']) : ?>
    <form action="/comment/add" method="POST">
        <input type="text" class="d-none" name="article" value="<?= $data['article']['id'] ?>">
        <div class="form-group">
            <p>Оставьте комментарий. Новые комментарии появляются после модерации<p>
            <textarea name="text" id="commentText" class="comment-input" rows="10"></textarea>
        </div>
        <button type="submit" class="blog-button">Отправить</button>
    </form>
    <?php else : ?>
    <div>
        Вы не авторизованы на сайте. Чтобы оставить комментарий <a href="/reg">зарегистрируйтесь</a> или <a href="/login">войдите</a>.
    </div>
    <?php endif; ?>
    <div class="comment-list">
        <?php foreach($data['comments'] as $comment) : ?>
            <?php if ($comment['approved'] || (!$comment['approved'] && $_SESSION['rights'] >= 2)) : ?>
                <div class="comment">
                    <img src="<?= $comment['avatar'] ?>" alt="аватар" class="comment-img">
                    <div class="comment-body">
                        <div class="comment-info"><span class="comment-info-username"><?= $comment['username'] ?></span>
                            <span class="comment-info-date"><?= $comment['date'] ?></span>
                            <?php if (!$comment['approved'] && $_SESSION['rights'] >= 2) : ?>
                                <span class="comment-info-nonapproved">Непроверенный комментарий</span>
                                <button class="comment-button-approve blog-button-icon" data-id="<?= $comment['id'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 new-comments-comment-check-svg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                                <button class="comment-button-delete blog-button-icon" data-id="<?= $comment['id'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x new-comments-comment-del-svg" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                        <div class="comment-text"><?= $comment['text'] ?></div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

</div>
    </div>
</div>

<?php require VIEW_DIR . '/layout/base/footer.php'; ?>