<?php require VIEW_DIR . '/layout/base/header.php'; ?>
    <div class="row">
        <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
        <div class="col-9 article-manager">
            <div class="modal fade" id="deletingModal" tabIndex="-1" aria-labelledby="deleteHeader" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteHeader">Уверены, что хотите удалить статью?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="blog-button" data-dismiss="modal">Отмена</button>
                            <button type="button" class="blog-button" id="buttonDeleteArticle">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="article-manager__header">Страница управления статьями</p>
            <div class="article-manager__container">
                <div class="article-manager__add">
                    <p class="article-manager__add-text">Добавить новую статью</p>
                    <a href="/admin/article/new" class="article-manager__add-link">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus article-manager__add-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </a>
                </div>
                <div class="article-manager__chunk chunk">
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
            </div>
            <?php foreach($data['articles'] as $article) : ?>
            <div class="admin-article">
                <div class="admin-article__content">
                    <p class="admin-article__header" id="nameArticle<?= $article['id'] ?>"><?= $article['name'] ?></p>
                    <p class="admin-article__author"><?= $article['author'] ?></p>
                </div>
                <a href="<?= '/admin/article/edit?id=' . $article['id'] ?>" class="admin-article__edit">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pen admin-article__edit-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                    </svg>
                </a>
                <button data-id="<?= $article['id'] ?>" type="button" class="admin-article__delete blog-button-icon" data-toggle="modal" data-target="#deletingModal">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash admin-article__delete-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button>
            </div>
            <?php endforeach; ?>
            <div class="article-manager__pagination blog-pagination">
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