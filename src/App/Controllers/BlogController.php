<?php

namespace App\Controllers;

use App\Config;
use App\Services\ArticleManager;
use App\Services\UserManager;
use App\Services\Auth;
use App\View\View;

class BlogController extends Controller
{
    public function loadBlogMainPage()
    {
        $data = [];

        $data['page'] = $_GET['page'] ?? 1;

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

        return new View('index', $data);
    }

    public function loadLoginPage()
    {
        return new View('login');
    }

    public function loadRegPage()
    {
        return new View('reg');
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/');
    }
}