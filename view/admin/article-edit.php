<?php require VIEW_DIR . './layout/base/header.php'; ?>
<link rel="stylesheet" href="/trumbowyg/dist/ui/trumbowyg.min.css">
<div class="row">
    <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
    <div class="col-9 article-editor-page">
        <h3>Редактирование статьи с id = <?= $data['article']['id'] ?></h3>

        <div class="article-editor-page__image-form">
            <label for="article-image-link">
                Установите ссылку на картинку, которая отобразится на главной странице
            </label>
            <input type="text" id="article-image-link" class="article-editor-page__image-input" value="<?= $data['article']['img'] ?>">
            <button class="article-editor-page__image-button" id="image-save">Сохранить</button>
            <div class="image-form-answer d-none"></div>
        </div>

        <div class="editor-control">
            <div class="editor-control__container">
                <button class="editor-control__button" id="editor-name">Название</button>
                <button class="editor-control__button" id="editor-descr">Описание</button>
                <button class="editor-control__button" id="editor-text">Текст</button>
            </div>
            <button class="editor-control__button" id="editor-save" data-id="<?= $data['article']['id'] ?>">Сохранить</button>
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

</div>

<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script src="/trumbowyg/dist/trumbowyg.min.js"></script>
<script src="/trumbowyg/dist/plugins/upload/trumbowyg.upload.min.js"></script>
<script src="/js/main.js"></script>

</body>
</html>
