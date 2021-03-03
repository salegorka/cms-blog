<?php require VIEW_DIR . '/layout/base/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-sm-8 p-0 article-container">
        <p class="profile-info">Редактирование профиля пользователя</p>
        <div class="profile">
            Профиль пользователе
            <p class="user-info-username">Имя пользователя: <?= $data['user']['username'] ?></p>
            <p class="user-info-role">Ваша роль на сайте: <?= $data['user']['role'] ?></p>
            <?php if (!empty($data['user']['avatar'])) : ?>
                <p class="user-info-img">Аватар:
                    <figure class="user-info-img-container">
                        <img class="user-info-img" src="<?= $data['user']['avatar'] ?>" alt="Аватар пользователя">
                    </figure>
                </p>
            <?php else: ?>
                <p>Аватар не установлен</p>
            <?php endif; ?>
            <form action="#" class="profile-edit-form">
                <div>
                    <label for="aboutInput" class="form-label">О себе</label>
                    <textarea class="profile-edit-about" name="about" id="aboutInput" class="form-control" cols="30" rows="10"><?= $data['user']['about'] ?></textarea>
                </div>
                <div>
                    <p class="profile-edit-form-about-answer d-none"></p>
                </div>
                <div>
                    <label for="fileInput">Загрузите аватар</label>
                    <input type="file" name="avatar" id="fileInput" class="profile-edit-file">
                </div>
                <div>
                    <p class="profile-edit-form-avatar-answer d-none"></p>
                </div>
                <div>
                    <input type="submit" value="Изменить" class="blog-button">
                </div>
            </form>
        </div>
    </div>
</div>
<?php require VIEW_DIR . '/layout/base/footer.php'; ?>