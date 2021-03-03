<?php require VIEW_DIR . '/layout/base/header.php'; ?>
    <div class="row">
        <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
        <div class="col-9 new-comments-panel">Страница модерирования новых комментариев

            <div class="new-comments__chunk chunk">
                <form action="#" class="chunk__form">
                    <p class="chunk__text">Количество элементов на странице</p>
                    <select name="" id="" class="chunk__select">
                        <option value="10" class="chunk__option">10</option>
                        <option value="20" class="chunk__option">20</option>
                        <option value="50" class="chunk__option">50</option>
                        <option value="200" class="chunk__option">200</option>
                        <option value="<?= $data['maxPage'] * $data['chunk'] ?>" class="chunk__option">Все</option>
                    </select>
                    <input type="submit" class="chunk__button blog-button" value="Загрузить"/>
                </form>
            </div>

            <div class="new-comments-list">
            <?php foreach($data['comments'] as $comment): ?>
                <div class="new-comments-comment">
                    <div class="new-comments-comment-info">
                        <p class="new-comments-comment-text"><?= $comment['text'] ?></p>
                        <p class="new-comments-comment-date"><?= $comment['date'] ?></p>
                        <p class="new-comments-comment-author"><?= $comment['username'] ?></p>
                        <p class="new-comments-comment-article"><?= $comment['article'] ?></p>
                    </div>
                    <button class="new-comments-comment-check blog-button-icon" data-id="<?= $comment['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 new-comments-comment-check-svg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </button>
                    <button class="new-comments-comment-delete blog-button-icon" data-id="<?= $comment['id'] ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x new-comments-comment-del-svg" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    </button>
                </div>
            <?php endforeach; ?>
            </div>

            <div class="new-comments__pagination blog-pagination">
                <ul class="pagination pagination-sm justify-content-center blog-pagination__ul">
                    <?php if ($data['page'] != 1) : ?>
                        <li class="page-item blog-pagination__li">
                            <a class="page-link article-pagination-link" href="<?= '/admin/article?chunk=' . $data['chunk'] . '&page=' . ($data['page'] - 1)  ?>">Назад</a>
                        </li>
                    <?php endif; ?>
                    <?php for($i = 1; $i <= $data['maxPage']; $i++) : ?>
                        <?php if ($i != $data['page']) : ?>
                            <li class="page-item blog-pagination__li">
                                <a class="page-link article-pagination-link" href="<?= '/admin/article?chunk=' . $data['chunk'] . '&page=' . $i  ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($data['page'] != $data['maxPage']) : ?>
                        <li class="page-item blog-pagination__li">
                            <a class="page-link article-pagination-link" href="<?= '/admin/article?chunk=' . $data['chunk'] . '&page=' . ($data['page'] + 1)  ?>">Вперед</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php require VIEW_DIR . '/admin/layout/footer.php'; ?>