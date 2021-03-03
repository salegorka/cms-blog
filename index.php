<?php

error_reporting(E_ALL);
ini_set('display_errors',true);
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

$router = new \App\Router();

$router->get('/',    function () {

    $data = [];

    if (!isset($_GET['page'])) {
        $data['page'] = 1;
    } else {
        $data['page'] =$_GET['page'];
    }

    $config = App\Config::getInstance();
    $data['chunk'] = $config->get('mainSettings.mainPageArticleCount');

    $articleManager = new App\Services\ArticleManager();
    $data['maxPage'] = $articleManager->countMaxPage($data['chunk']);
    $data['articles'] = $articleManager->loadArticlesToPage($data['chunk'], $data['page']);

    if (isset($_SESSION['isUserAuthorized']) && $_SESSION['isUserAuthorized']) {
        $userManager = new App\Services\UserManager();
        $data['user'] = $userManager->loadUserSubInfo($_SESSION['userId']);
    } else {
        $data['user'] = null;
    }

    $controller = new App\Controller('index', $data);
    return $controller->render();
});
$router->get('/page/*', function ($pageName) {
    $config = App\Config::getInstance();
    $menu = $config->get('mainSettings.menu');

    $activePage = [];
    foreach ($menu as $item) {
        if ($pageName == $item['link']) {
            $activePage = $item;
        }
    }

    if (empty($activePage)) {
        throw new App\Exception\NotFoundException();
    }

    $pagesManager = new App\Services\PagesManager();
    $data['content'] = $pagesManager->loadPageContent($activePage['id']);

    $controller = new App\Controller('staticPage', $data);
    return $controller->render();
});

$router->get('/login', function () {
    $controller = new App\Controller('login');
    return $controller->render();
});
$router->post('/login', function () {
    try {
        $result = App\Services\Auth::authUser($_POST['loginEmailInput'], $_POST['loginPassInput']);
        header('Location: /');
    } catch (Exception $e) {
        $error = $e->getMessage();
        $controller = new App\Controller('login', ['error' => $error]);
        return $controller->render();
    }
});
$router->get('/reg', function () {
    $controller = new App\Controller('reg');
    return $controller->render();
});
$router->post('/reg', function () {
    if (!(isset($_POST['regCheck']) && $_POST['regCheck'] == 1)) {
        $controller = new App\Controller('reg', ['error' => ['regCheck' => 'Необходимо согласиться с правилами сайта']]);
        return $controller->render();
    }

    try {
        $result = App\Services\Reg::regNewUser($_POST['regEmailInput'], $_POST['regUsernameInput'],
            $_POST['regPassInput'], $_POST['regPassControlInput']);
        header('Location: /');
    } catch(App\Exception\RegException $e) {
        $error = $e->error;
        $controller = new App\Controller('reg', ['error' => $error]);
        return $controller->render();
    }
    return 0;
});
$router->get('/profile', function () {
    if (!$_SESSION['isUserAuthorized']) {
        throw new App\Exception\BadAuthorizedException();
    }

    $data['id'] = $_SESSION['userId'];
    try {
        $userManager = new App\Services\UserManager();
        $data['user'] = $userManager->findUserById($data['id']);
    } catch (Exception $e) {
        throw new App\Exception\NotFoundException();
    }

    $controller = new App\Controller('profile', $data);
    return $controller->render();
});
$router->get('/profile/edit', function () {
    if (!$_SESSION['isUserAuthorized']) {
        throw new App\Exception\BadAuthorizedException();
    }

    $data['id'] = $_SESSION['userId'];
    try {
        $userManager = new App\Services\UserManager();
        $data['user'] = $userManager->findUserById($data['id']);
    } catch (Exception $e) {
        throw new App\Exception\NotFoundException();
    }

    $controller = new App\Controller('profile-edit', $data);
    return $controller->render();
});
$router->post('/profile/edit', function () {
    if (!$_SESSION['isUserAuthorized']) {
        throw new \App\Exception\BadAuthorizedException();
    }

    $userManager = new App\Services\UserManager();

    $answer = [];

    if (isset($_POST['about'])) {
        $about = clean($_POST['about']);
        if (mb_strlen($about) > 0) {
            $userManager->setUserAbout($_SESSION['userId'], $_POST['about']);
        }
    }


    if (!empty($_FILES['avatar']['name'])) {
        $fileTypes = ["image/jpeg", "image/png"];

        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $answer['error']['errorAvatar'] = "Внутрення ошибка сервера. Код ошибки:" . $_FILES['avatar']['error'];
        }
        elseif (!in_array($_FILES['avatar']['type'], $fileTypes))
        {
            $answer['error']['errorAvatar'] = "Ошибка: неверный формат файла! Разрешены:" . implode(" ", $fileTypes) . ". Файл " . $_FILES['avatar']['name'] . " не картинка!";
        }
        elseif ($_FILES['avatar']['size'] > 2*1e6) {
            $answer['error']['errorAvatar'] = "Ошибка: Максимальный размер файла 2Мб. Файл " . $_FILES['avatar']['name'] . " слишком большого размера!";
        }

        if (!isset($answer['error'])) {
            $dirname = $_SERVER['DOCUMENT_ROOT'] . '/img/avatars';
            $tmpname = $_FILES['avatar']['tmp_name'];
            $name = 'avatar' . $_SESSION['userId'] . mb_substr($_FILES['avatar']['name'], -4, 4);
            move_uploaded_file($tmpname, "$dirname/$name");
            $link = '/img/avatars/' . $name;
            $userManager->setUserAvatar($_SESSION['userId'], $link);
        }
    }

    if (!isset($answer['error'])) {
        $answer['ok'] = true;
        echo json_encode($answer);
    } else {
        echo json_encode($answer);
    }


});
$router->get("/profile/subscribe/on", function () {
    if (!$_SESSION['isUserAuthorized']) {
        throw new \App\Exception\BadAuthorizedException();
    }

    $subscribeManager = new App\Services\SubscribeManager();
    $subscribeManager->subscribeUserOn($_SESSION['userId']);

    header("HTTP/1.0 200 OK");
});
$router->get("/profile/subscribe/off", function () {
    if (!$_SESSION['isUserAuthorized']) {
        throw new \App\Exception\BadAuthorizedException();
    }

    $subscribeManager = new App\Services\SubscribeManager();
    $subscribeManager->subscribeUserOff($_SESSION['userId']);

    header("HTTP/1.0 200 OK");
});
$router->post("/subscriber/new", function () {

    $email = $_POST['email'];

    $subscriberManager = new App\Services\SubscribeManager();

    if ($subscriberManager->subscriberCheck($email)) {
        return json_encode(['error' => "Подписчик с таким email уже подписан на обновления"]);
    } else {
        $subscriberManager->subscriberAdd($email);
        return json_encode(['ok' => true]);
    }

});
$router->get("/subscriber/del", function () {
    if (!(isset($_GET['id']) && $_GET['token'])) {
        throw new App\Exception\NotFoundException();
    }

    $subscriberManager = new App\Services\SubscribeManager();
    $subscriberManager->subscriberDelById($_GET['id'], $_GET['token']);

    $controller = new App\Controller("notificationCancel");
    return $controller->render();
});
$router->get('/article', function () {
    $id = 0;
    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    } else {
        $id = $_GET['id'];
    }

    $articleManager = new App\Services\ArticleManager();
    $data = $articleManager->loadArticleToView($id);

    $controller = new App\Controller('article', $data);
    return $controller->render();
});
$router->post('/comment/add', function () {
    if (!$_SESSION['isUserAuthorized']) {
       throw new \App\Exception\BadAuthorizedException();
    }

    $commentManager = new App\Services\CommentManager();
    $commentManager->addComment($_SESSION['userId'], $_POST['article'], $_POST['text']);

    header('Location: /article?id=' . $_POST['article']);
});
$router->get('/logout', function () {
    App\Services\Auth::logout();
    header('Location: /');
});
$router->get('/admin', function () {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $settings = App\Config::getInstance();
    $data['mainPageArticleCount'] = $settings->get('mainSettings.mainPageArticleCount');

    $controller = new App\Controller('admin.main', $data);
    return $controller->render();

});
$router->get("/admin/settings", function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $config = App\Config::getInstance();
    $config->updateMainArticlesCount( $_GET['count']);

    header("HTTP/1.0 200 OK");
});
$router->get('/admin/article', function () {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['chunk'])) {
        $data['chunk'] = 20;
    } else {
        $data['chunk'] = $_GET['chunk'];
    }

    if (!isset($_GET['page'])) {
        $data['page'] = 1;
    } else {
        $data['page'] = $_GET['page'];
    }

    $articleManager = new App\Services\ArticleManager();

    $data['maxPage'] = $articleManager->countMaxPageAdmin($data['chunk']);
    $data['articles'] = $articleManager->loadArticlesToAdminPage($data['chunk'], $data['page']);

    $controller = new App\Controller('admin.article.article', $data);
    return $controller->render();

});
$router->get('/admin/article/edit', function () {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!(isset($_GET['id']))) {
        throw new App\Exception\NotFoundException();
    }

    $articleManager = new App\Services\ArticleManager();

    $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

    $controller = new App\Controller('admin.article.article-edit', $data);
    return $controller->render();
});
$router->post('/admin/article/edit', function () {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $id = $_POST['id'];
    $data = [];

    if (isset($_POST['name'])) {
        $data['name'] = $_POST['name'];
    }

    if (isset($_POST['descr'])) {
        $data['description'] = $_POST['descr'];
    }

    if (isset($_POST['text'])) {
        $data['text'] = $_POST['text'];
    }

    if (isset($_POST['img'])) {
        $data['img'] = $_POST['img'];
    }

    $articleManager = new App\Services\ArticleManager();
    $message = "";

    try {
        $message = $articleManager->updateArticle($id, $data);
    } catch (Exception $e) {
        $message = "Статья с таким id не найдена";
    }


    //echo json_encode(['message' => $_POST]);
    echo json_encode(['message' => $message]);

});
$router->get('/admin/article/new', function() {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $articleManager = new \App\Services\ArticleManager();
    $id = $articleManager->createNewArticle();

    header('Location: /admin/article/edit?id=' . $id);

});
$router->get('/admin/article/show', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    }

    $articleManager= new \App\Services\ArticleManager();
    $articleManager->showArticle($_GET['id']);

    header("HTTP/1.0 200 OK");

});
$router->get('/admin/article/hide', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    }

    $articleManager= new \App\Services\ArticleManager();
    $articleManager->hideArticle($_GET['id']);

    header("HTTP/1.0 200 OK");
});
$router->get('/admin/article/notification', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    }

    $articleManager = new App\Services\ArticleManager();
    $data['article'] = $articleManager->loadSingleArticle($_GET['id']);

    $subscribeManager = new App\Services\SubscribeManager();
    $subscribeManager->sendNotification($data['article']['id'], $data['article']['name'], $data['article']['description']);

    echo json_encode(['ok' => 'Уведомление успешно отправлено']);
});
$router->get('/admin/article/delete', function(){

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    }

    $articleManager = new App\Services\ArticleManager();
    $articleManager->deleteArticle($_GET['id']);

    header("HTTP/1.0 200 OK");

});
$router->get('/admin/pages', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $config = App\Config::getInstance();
    $menu = $config->get("mainSettings.menu");

    $data = [];
    $data['pages'] = [];
    foreach($menu as $item) {
        $data['pages'][] = $item;
    }

    $controller = new App\Controller('admin.staticPage.main', $data);
    return $controller->render();

});
$router->get('/admin/pages/edit', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $data = [];
    $data['id'] = 0;

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    } else {
        $data['id'] = $_GET['id'];
    }

    $config = App\Config::getInstance();
    $menu = $config->get("mainSettings.menu");

    foreach($menu as $item) {
        if ($item['id'] == $data['id']) {
            $data['name'] = $item['name'];
            $data['link'] = $item['link'];
        }
    }

    $pageController = new App\Services\PagesManager();
    $data['content'] = $pageController->loadPageContent($data['id']);

    $controller = new App\Controller('admin.staticPage.edit', $data);
    return $controller->render();

});
$router->post('/admin/pages/edit', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $data = $_POST;

    $pagesManager = new App\Services\PagesManager();
    $message = $pagesManager->updatePage($data['id'], $data);

    echo json_encode(['message' => $message]);

});
$router->get('/admin/page/new', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $pagesManager = new App\Services\PagesManager();
    $id = $pagesManager->createNewPage();

    header('Location: /admin/pages/edit?id=' . $id);

});
$router->get('/admin/page/delete', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $deletingArticleId = 0;

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    } else {
        $deletingArticleId = $_GET['id'];
    }

    $pageManager = new App\Services\PagesManager();
    $pageManager->deletePage($deletingArticleId);

    header("HTTP/1.0 200 OK");

});
$router->get('/admin/comments', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['chunk'])) {
        $data['chunk'] = 20;
    } else {
        $data['chunk'] = $_GET['chunk'];
    }

    if (!isset($_GET['page'])) {
        $data['page'] = 1;
    } else {
        $data['page'] = $_GET['page'];
    }

    $commentManager = new App\Services\CommentManager();
    $data['maxPage'] = $commentManager->countAllCommentsPage($data['chunk']);
    $data['comments'] = $commentManager->loadAllComments($data['chunk'], $data['page']);

    $controller = new App\Controller('admin.comments.main', $data);
    return $controller->render();
});
$router->get('/admin/comments/delete', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $deletingCommentId = 0;

    if (!isset($_GET['id'])) {
        throw new App\Exception\NotFoundException();
    } else {
        $deletingCommentId = $_GET['id'];
    }

    $commentManager = new App\Services\CommentManager();
    $commentManager->deleteComment($deletingCommentId);

    header("HTTP/1.0 200 OK");

});
$router->get('/admin/comments/new', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['chunk'])) {
        $data['chunk'] = 20;
    } else {
        $data['chunk'] = $_GET['chunk'];
    }

    if (!isset($_GET['page'])) {
        $data['page'] = 1;
    } else {
        $data['page'] = $_GET['page'];
    }

    $commentManager = new App\Services\CommentManager();
    $data['comments'] = $commentManager->loadNewComments($data['chunk'], $data['page']);
    $data['maxPage'] = $commentManager->countNewCommentsPage($data['chunk']);

    $controller = new App\Controller('admin.comments.new', $data);
    return $controller->render();
});
$router->get('/admin/comments/new/check', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $commentManager = new App\Services\CommentManager();
    $commentManager->approveComment($_GET['id']);

    header("HTTP/1.0 200 OK");
});
$router->get('/admin/comments/new/delete', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $commentManager = new App\Services\CommentManager();
    $commentManager->deleteComment($_GET['id']);

    header("HTTP/1.0 200 OK");
});
$router->get( '/admin/users', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
        throw new App\Exception\BadAuthorizedException();
    }

    if (!isset($_GET['chunk'])) {
        $data['chunk'] = 20;
    } else {
        $data['chunk'] = $_GET['chunk'];
    }

    if (!isset($_GET['page'])) {
        $data['page'] = 1;
    } else {
        $data['page'] = $_GET['page'];
    }

    $userManager = new App\Services\UserManager();
    $data['maxPage'] = $userManager->countUsersPage($data['chunk']);
    $data['users'] = $userManager->loadUsers($data['chunk'], $data['page']);

    $controller = new App\Controller('admin.users.main', $data);
    return $controller->render();
});
$router->get('/admin/users/find', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $data = [];
    $userManager = new App\Services\UserManager();
    if (isset($_GET['username'])) {
        try {
            $data['user'] = $userManager->findUserByUsername($_GET['username']);
            $data['modelFound'] = true;
        } catch ( Exception $e) {
            $data['modelFound'] = false;
        }
    } elseif (isset($_GET['id'])) {
        try {
            $data['user'] = $userManager->findUserById($_GET['id']);
            $data['modelFound'] = true;
        } catch ( Exception $e) {
            $data['modelFound'] = false;
        }
    }

    $controller = new App\Controller('admin.users.userInfo', $data);
    return $controller->render();
});
$router->get('/admin/users/delete', function () {
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $userManager = new App\Services\UserManager();
    $userManager->deleteUser($_GET['id']);

    header("HTTP/1.0 200 OK");
});
$router->get('/admin/users/role', function (){
    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $userManager = new App\Services\UserManager();
    $userManager->setUserRole($_GET['id'], $_GET['role']);

    header("HTTP/1.0 200 OK");
});


$application = new \App\Application($router);

$application->run();
