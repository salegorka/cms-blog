<?php require VIEW_DIR . '/layout/base/header.php'; ?>
<div class="row">
    <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
    <div class="col-9 admin-panel">
        <p>
            Страница управления содержимым сайта. Для переключения между разделами используйте меню справа.
        </p>
        <div class="admin-panel-settings">
            <p class="admin-panel-setting-info">
                Установите количество статей отображаемых на главной странице
            </p>
            <div class="admin-panel-setting-form">
                <select class="admin-panel-settings-select">
                    <?php $data['options'] = [1, 2, 3, 5, 7, 10];
                    foreach ($data['options'] as $option) : ?>
                        <option <?= $data['mainPageArticleCount'] == $option ? 'selected' : '' ?> value="<?= $option ?>"><?= $option ?></option>
                    <?php endforeach; ?>
                </select>
                <button class="blog-button admin-panel-settings-button">Установить</button>
            </div>
        </div>
    </div>
</div>
<?php require VIEW_DIR . '/admin/layout/footer.php'; ?>