<?php require VIEW_DIR . './layout/base/header.php'; ?>
<link rel="stylesheet" href="/trumbowyg/dist/ui/trumbowyg.min.css">
<div class="row">
    <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
    <div class="col-9 page-editor">
        <p class="page-editor__header">Редактирование статьи с id = <span class="page-editor__page-id" ><?= $data['id'] ?></span></p>

        <div class="page-editor-control">
            <div class="">
                <p>Установите название страницы в меню</p>
                <input type="text" class="page-editor-control__name-input" value="<?= $data['name'] ?>">
            </div>
            <div class="">
                <p>Установите окончание ссылки на страницу (ссылка будет выглядеть как "/page/link")</p>
                <input type="text" class="page-editor-control__link-input" value="<?= $data['link'] ?>">
            </div>

            <div>
                <button class="page-editor-control__save blog-button">Сохранить</button>
                <p class="page-editor-control__answer d-none"></p>
            </div>

            <div>
                <p>Редактирование контента страницы</p>

                <div class="page-editor__editor">
                    <?= $data['content'] ?>
                </div>
            </div>

        </div>

    </div>
    <?php require VIEW_DIR . './admin/layout/footerTrumb.php'; ?>
