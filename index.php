<?php

error_reporting(E_ALL);
ini_set('display_errors',true);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bootstrap.php';

$router = new \App\Router();

$router->get('/',    function () {
    $controller = new App\Controller('index');
    return $controller->render();
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
