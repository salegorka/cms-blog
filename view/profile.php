<?php require VIEW_DIR . '/layout/base/header.php'; ?>
    <div class="row justify-content-center">
        <div class="col-sm-8 p-0 article-container">
            <p class="profile-info">Профиль пользователя</p>
            <div class="profile">
                <p class="user-info-email">Электронная почта: <?= $data['user']['email'] ?></p>
                <p class="user-info-username">Имя пользователя: <?= $data['user']['username'] ?></p>
                <p class="user-info-about">О себе: <?= $data['user']['about'] ?></p>
                <?php if (!empty($data['user']['avatar'])) : ?>
                    <p class="user-info-img">Аватар:
                        <figure class="user-info-img-container">
                            <img class="user-info-img" src="<?= $data['user']['avatar'] ?>" alt="Аватар пользователя">
                        </figure>
                    </p>
                <?php else: ?>
                    <p>Аватар не установлен</p>
                <?php endif; ?>
                <p class="user-info-role">Ваша роль на сайте: <?= $data['user']['role'] ?></p>
            </div>
            <div class="profile-edit-link">
                <a href="/profile/edit" class="blog-button">Редактировать профиль</a>
            </div>
            <div class="profile-subscribe">
                <?php if ($data['user']['subscribe']) : ?>
                    <p class="profile-subscribe-info">Вы подписаны на рассылку</p>
                    <button class="blog-button profile-subscribe-button-off">Отписаться</button>
                <?php else : ?>
                    <p class="profile-subscribe-info">Вы не подписаны на рассылку</p>
                    <button class="blog-button profile-subscribe-button-on">Подписаться</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php require VIEW_DIR . '/layout/base/footer.php'; ?>