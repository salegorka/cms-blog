<?php

namespace App\Controllers;

use App\Config;
use App\Exception\NotFoundException;
use App\Exception\BadAuthorizedException;
use App\Services\PagesManager;
use App\View\View;

class PagesController extends Controller {

    public static function loadPageToView($pageName)
    {

        $config = Config::getInstance();
        $menu = $config->get('mainSettings.menu');

        $activePage = [];
        foreach ($menu as $item) {
            if ($pageName == $item['link']) {
                $activePage = $item;
            }
        }

        if (empty($activePage)) {
            throw new NotFoundException();
        }

        $pagesManager = new PagesManager();
        $data['content'] = $pagesManager->loadPageContent($activePage['id']);

        return new View('staticPage', $data);

    }

    public static function loadPageToEdit()
    {

        parent::checkContentManagerRights();

        $data = [];
        $data['id'] = 0;

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $data['id'] = $_GET['id'];
        }

        $config = Config::getInstance();
        $menu = $config->get("mainSettings.menu");

        foreach($menu as $item) {
            if ($item['id'] == $data['id']) {
                $data['name'] = $item['name'];
                $data['link'] = $item['link'];
            }
        }

        $pageController = new PagesManager();
        $data['content'] = $pageController->loadPageContent($data['id']);

        return new View('admin.staticPage.edit', $data);

    }

    public static function updatePage()
    {

        parent::checkContentManagerRights();

        $data = $_POST;

        $pagesManager = new PagesManager();
        $message = $pagesManager->updatePage($data['id'], $data);

        echo json_encode(['message' => $message]);

    }

    public static function createPage()
    {

        parent::checkContentManagerRights();

        $pagesManager = new PagesManager();
        $id = $pagesManager->createNewPage();

        header('Location: /admin/pages/edit?id=' . $id);

    }

    public static function deletePage()
    {

        parent::checkContentManagerRights();

        $deletingPageId = 0;

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $deletingPageId = $_GET['id'];
        }

        $pageManager = new PagesManager();
        $pageManager->deletePage($deletingPageId);

        header("HTTP/1.0 200 OK");

    }

}