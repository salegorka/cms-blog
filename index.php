<?php

error_reporting(E_ALL);
ini_set('display_errors',true);
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

$router = new \App\Router();

$router->get('/',    function () {
    $controller = new App\Controller('index');
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
    try {
        $result = App\Services\Reg::regNewUser($_POST['regEmailInput'], $_POST['regUsernameInput'],
            $_POST['regPassInput'], $_POST['regPassControlInput']);
        header('Location: /');
    } catch(Exception $e) {
        $error = $e->getMessage();
        $controller = new App\Controller('reg', ['error' => $error]);
        return $controller->render();
    }
    return '123123';
});
$router->get('/logout', function () {
    App\Services\Auth::logout();
    header('Location: /');
});
$router->get('/admin', function () {

    if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
        throw new App\Exception\BadAuthorizedException();
    }

    $controller = new App\Controller('admin.main');
    return $controller->render();

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

    $data['maxPage'] = $articleManager->countMaxPage($data['chunk']);
    $data['articles'] = $articleManager->loadArticlesToPage($data['chunk'], $data['page']);

    $controller = new App\Controller('admin.article', $data);
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

    $controller = new App\Controller('admin.article-edit', $data);
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
$router->get('/about', function() {
    return 'about';
});
$router->get('/books', function () {
    $controller = new App\Controller('books');
    return $controller->render();
});
$router->get('/test/*/test2/*', function ($param1, $param2) {
    return "Test page with param1=$param1 param2=$param2";
});
$router->post('/data', function () {
    return $_POST;
});

$application = new \App\Application($router);


$application->run();
