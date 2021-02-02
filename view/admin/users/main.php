<?php require VIEW_DIR . './layout/base/header.php'; ?>
    <div class="row">
        <?php require VIEW_DIR . '/admin/admin-menu.php' ?>
        <div class="col-9 users-panel">Страница управления пользователями и их ролями

            <div class="users-find">
                <form action="/admin/users/find" method="GET">
                    <label for="searchInput" class="form-label">Поиск пользователя</label>
                    <input type="text" class="form-control" id="searchInput" name="username" aria-describedby="searchHelp">
                    <div id="searchHelp" class="form-text">Введите имя пользователя</div>
                    <input type="submit" class="blog-button users-find-button" value="Искать">
                </form>
            </div>

            <div class="users-chunk chunk">
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

            <div class="users-list">
                <?php foreach($data['users'] as $user): ?>
                    <div class="users-user">
                        <div class="users-user-info">
                            <p class="users-user-username"><?= $user['username'] ?></p>
                            <p class="users-user-email"><?= $user['email'] ?></p>
                            <p class="users-user-role"><?= $user['role'] ?></p>
                        </div>
                        <a href="<?= '/admin/users/find?id=' . $user['id'] ?>" class="users-user-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square users-user-edit-svg" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="users-pagination blog-pagination">
                <ul class="pagination pagination-sm justify-content-center blog-pagination__ul">
                    <?php if ($data['page'] != 1) : ?>
                        <li class="page-item blog-pagination__li">
                            <a class="page-link article-pagination-link" href="<?= '/admin/users?chunk=' . $data['chunk'] . '&page=' . ($data['page'] - 1)  ?>">Назад</a>
                        </li>
                    <?php endif; ?>
                    <?php for($i = 1; $i <= $data['maxPage']; $i++) : ?>
                        <?php if ($i != $data['page']) : ?>
                            <li class="page-item blog-pagination__li">
                                <a class="page-link article-pagination-link" href="<?= '/admin/users?chunk=' . $data['chunk'] . '&page=' . $i  ?>"><?= $i ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <?php if ($data['page'] != $data['maxPage']) : ?>
                        <li class="page-item blog-pagination__li">
                            <a class="page-link article-pagination-link" href="<?= '/admin/users?chunk=' . $data['chunk'] . '&page=' . ($data['page'] + 1)  ?>">Вперед</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>
    </div>
<?php require VIEW_DIR . './admin/layout/footer.php'; ?>