<?php require VIEW_DIR . './layout/base/header.php'; ?>
    <div class="row">
        <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
        <div class="col-9 user-info-panel">

            <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserLabel">Вы действительно хотите удалить этого пользователя?</h5>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="blog-button" data-dismiss="modal">Нет</button>
                            <button type="button" class="blog-button" data-dismiss="modal" data-id="<?= $data['user']['id'] ?>" id="deleteUserButton">Да</button>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($data['modelFound']): ?>
            <div class="user-info-div">
                Информация о пользователе
                <p class="user-info-email">Электронная почта: <?= $data['user']['email'] ?></p>
                <p class="user-info-username">Имя пользователя: <?= $data['user']['username'] ?></p>
                <p class="user-info-about">О себе: <?= $data['user']['about'] ?></p>
                <p class="user-info-img">Аватар: <img src="<?= $data['user']['avatar'] ?>" alt="Аватар пользователя"></p>
                <p class="user-info-role">Роль пользователя на сайте: <?= $data['user']['role'] ?></p>
            </div>
            <div class="user-info-edit">
                Управление пользователем
                <div class="user-info-edit-form">
                <button class="blog-button user-info-delete-button" data-toggle="modal" data-target="#deleteUserModal">Удалить пользователя</button>
                <p class="user-info-edit-form-info">Изменить роль пользователя</p>
                <select name="" id="" class="user-info-select">
                    <option value="1">Зарегистрированный пользователь</option>
                    <option value="2">Контент-менеджер</option>
                    <option value="3">Администратор</option>
                </select>
                <button class="blog-button user-info-select-button" data-id="<?= $data['user']['id'] ?>">Установить</button>
                </div>
            </div>
            <?php else :?>
            <p class="user-info-userNotFound">Пользователь с такими данными не найден</p>
            <?php endif; ?>

        </div>
    </div>
<?php require VIEW_DIR . './admin/layout/footer.php'; ?>