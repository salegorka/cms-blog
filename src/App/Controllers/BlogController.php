<?php

namespace App\Controllers;

use App\Config;
use App\Services\ArticleManager;
use App\Services\UserManager;
use App\Services\Auth;
use App\Controller;

class BlogController
{

    public static function loadBlogMainPage() {

        $data = [];

        if (!isset($_GET['page'])) {
            $data['page'] = 1;
        } else {
            $data['page'] = $_GET['page'];
        }

        $config = Config::getInstance();
        $data['chunk'] = $config->get('mainSettings.mainPageArticleCount');

        $articleManager = new ArticleManager();
        $data['maxPage'] = $articleManager->countMaxPage($data['chunk']);
        $data['articles'] = $articleManager->loadArticlesToPage($data['chunk'], $data['page']);

        if (isset($_SESSION['isUserAuthorized']) && $_SESSION['isUserAuthorized']) {
            $userManager = new UserManager();
            $data['user'] = $userManager->loadUserSubInfo($_SESSION['userId']);
        } else {
            $data['user'] = null;
        }

        $controller = new Controller('index', $data);
        return $controller->render();

    }

    public static function loadLoginPage() {

        $controller = new Controller('login');
        return $controller->render();

    }

    public static function loadRegPage() {

        $controller = new Controller('reg');
        return $controller->render();

    }

    public static function logout() {

        Auth::logout();
        header('Location: /');

    }

}