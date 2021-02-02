<?php require VIEW_DIR . './layout/base/header.php'; ?>
<link rel="stylesheet" href="/trumbowyg/dist/ui/trumbowyg.min.css">
<div class="row">
    <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
    <div class="col-9 article-editor-page">
        <h3>Редактирование статьи с id = <?= $data['article']['id'] ?></h3>

        <div class="article-editor-shown">
            <?php if ($data['article']['shown']) : ?>
                <span class="article-editor-shown-text">Статья отображается</span>
                <button class="blog-button article-editor-button-show-off" data-id="<?= $data['article']['id'] ?>">Скрыть</button>
            <?php else : ?>
                <span class="article-editor-shown-text">Статья скрыта</span>
                <button class="blog-button article-editor-button-show-on" data-id="<?= $data['article']['id'] ?>">Показать</button>
            <?php endif; ?>
        </div>

        <div class="article-editor-notification">
            <span class="article-editor-notifcation-text">Послать письма подписчикам о выходе статьи</span>
            <button class="blog-button article-editor-notification-button" data-id="<?= $data['article']['id'] ?>">Рассылка</button>
            <div class="article-editor-notification-answer"></div>
        </div>

        <div class="article-editor-page__image-form">
            <label for="article-image-link">
                Установите ссылку на картинку, которая отобразится на главной странице
            </label>
            <input type="text" id="article-image-link" class="article-editor-page__image-input" value="<?= $data['article']['img'] ?>">
            <button class="article-editor-page__image-button blog-button" id="image-save">Сохранить</button>
            <div class="image-form-answer d-none"></div>
        </div>

        <div class="editor-control">
            <div class="editor-control__container">
                <button class="editor-control__button blog-button" id="editor-name">Название</button>
                <button class="editor-control__button blog-button" id="editor-descr">Описание</button>
                <button class="editor-control__button blog-button" id="editor-text">Текст</button>
            </div>
            <button class="editor-control__button blog-button" id="editor-save" data-id="<?= $data['article']['id'] ?>">Сохранить</button>
            <div class="editor-control__answer d-none">
            </div>
        </div>

        <div class="article-content d-none">
            <div class="article-content__name"><?= $data['article']['name'] ?></div>
            <div class="article-content__description"><?= $data['article']['description'] ?></div>
            <div class="article-content__text"><?= $data['article']['text'] ?></div>
        </div>

        <div class="article-editor">
        </div>

    </div>
</div>


<?php require VIEW_DIR . './admin/layout/footerTrumb.php'; ?>
