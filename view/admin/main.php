<?php require VIEW_DIR . './layout/base/header.php'; ?>
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
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="5">5</option>
                    <option value="7">7</option>
                    <option value="10">10</option>
                </select>
                <button class="blog-button admin-panel-settings-button">Установить</button>
            </div>
        </div>
    </div>
</div>
<?php require VIEW_DIR . './admin/layout/footer.php'; ?>