<?php

namespace App\Controllers;


use App\Config;
use App\Controller;
use App\Exception\BadAuthorizedException;
use App\Services\ArticleManager;
use App\Services\CommentManager;
use App\Services\UserManager;

class AdminController {

    public static function loadAdminMainPage() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $settings = Config::getInstance();
        $data['mainPageArticleCount'] = $settings->get('mainSettings.mainPageArticleCount');

        $controller = new Controller('admin.main', $data);
        return $controller->render();

    }

    public static function updateSettingsAdmin() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $config = Config::getInstance();
        $config->updateMainArticlesCount( $_GET['count']);

        header("HTTP/1.0 200 OK");

    }

    public static function loadArticlesList() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
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

        $articleManager = new ArticleManager();

        $data['maxPage'] = $articleManager->countMaxPageAdmin($data['chunk']);
        $data['articles'] = $articleManager->loadArticlesToAdminPage($data['chunk'], $data['page']);

        $controller = new Controller('admin.article.article', $data);
        return $controller->render();

    }

    public static function loadPageList() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
        }

        $config = Config::getInstance();
        $menu = $config->get("mainSettings.menu");

        $data = [];
        $data['pages'] = [];
        foreach($menu as $item) {
            $data['pages'][] = $item;
        }

        $controller = new Controller('admin.staticPage.main', $data);
        return $controller->render();

    }

    public static function loadAllCommentList() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
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

        $commentManager = new CommentManager();
        $data['maxPage'] = $commentManager->countAllCommentsPage($data['chunk']);
        $data['comments'] = $commentManager->loadAllComments($data['chunk'], $data['page']);

        $controller = new Controller('admin.comments.main', $data);
        return $controller->render();

    }

    public static function loadNewCommentList() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 2)) {
            throw new BadAuthorizedException();
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

        $commentManager = new CommentManager();
        $data['comments'] = $commentManager->loadNewComments($data['chunk'], $data['page']);
        $data['maxPage'] = $commentManager->countNewCommentsPage($data['chunk']);

        $controller = new Controller('admin.comments.new', $data);
        return $controller->render();

    }

    public static function loadUserList() {

        if (!(isset($_SESSION['rights']) && $_SESSION['rights'] >= 3)) {
            throw new BadAuthorizedException();
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

        $userManager = new UserManager();
        $data['maxPage'] = $userManager->countUsersPage($data['chunk']);
        $data['users'] = $userManager->loadUsers($data['chunk'], $data['page']);

        $controller = new Controller('admin.users.main', $data);
        return $controller->render();

    }

}