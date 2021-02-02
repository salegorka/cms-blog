<div class="col-3 admin-menu">
    <ul class="admin-menu__nav">
        <li class="admin-menu__li"><a href="/admin" class="admin-menu__link">Главная</a></li>
        <li class="admin-menu__li"><a href="/admin/article" class="admin-menu__link">Управление статьями</a></li>
        <li class="admin-menu__li"><a href="/admin/pages" class="admin-menu__link">Управление меню сайта и статичными страницами</a></li>
        <li class="admin-menu__li"><a href="/admin/comments" class="admin-menu__link">Управление комментариями</a></li>
        <li class="admin-menu__li"><a href="/admin/comments/new" class="admin-menu__link">Управление новыми комментариями</a></li>
        <?php if ($_SESSION['rights'] >= 3) :?>
        <li class="admin-menu-li"><a href="/admin/users" class="admin-menu-link">Управление пользователями</a></li>
        <?php endif; ?>
    </ul>
</div>