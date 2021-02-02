<?php require VIEW_DIR . './layout/base/header.php'; ?>
    <div class="row">
        <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
        <div class="col-9 admin-page">
            <div class="modal fade" id="deletingModal" tabIndex="-1" aria-labelledby="deleteHeader" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteHeader">Уверены, что хотите удалить страницу?</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="deletingPageName"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="blog-button" data-dismiss="modal">Отмена</button>
                            <button type="button" class="blog-button" id="buttonDeletePage">Удалить</button>
                        </div>
                    </div>
                </div>
            </div>
            <p class="admin-page__header">Страница управления статичными страницами и меню</p>
            <div class="page-admin-manager">
                <div class="page-admin-manager__add">
                    <p class="page-admin-manager__add-text">Добавить новую страницу</p>
                    <a href="/admin/page/new" class="page-admin-manager__add-link">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-plus page-admin-manager__add-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="page-admin-container">
                <?php foreach($data['pages'] as $page): ?>
                <div class="page-admin">
                    <div class="page-admin__info">
                        <p class="page-admin__name" id="deletingPageNameId<?= $page['id'] ?>"><?= $page['name'] ?></p>
                        <p class="page-admin__link"><?= $page['link'] ?></p>
                    </div>
                    <a href="<?= '/admin/pages/edit?id=' . $page['id'] ?>" class="page-admin__edit">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pen page-admin__edit-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M13.498.795l.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
                        </svg>
                    </a>
                    <button type="button" data-id="<?= $page['id'] ?>" class="page-admin__delete blog-button-icon" data-toggle="modal" data-target="#deletingModal">
                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash page-admin__delete-svg" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php require VIEW_DIR . './admin/layout/footerTrumb.php'; ?>