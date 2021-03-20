<?php

namespace App\Controllers;

use App\Config;
use App\Exception\NotFoundException;
use App\Services\PagesManager;
use App\View\JsonResponse;
use App\View\View;

class PagesController extends Controller
{
    public function loadPageToView($pageName)
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

    public function loadPageToEdit()
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

    public function updatePage()
    {
        parent::checkContentManagerRights();

        $data = $_POST;

        $pagesManager = new PagesManager();
        $response = $pagesManager->updatePage($data);

        return new JsonResponse($response);
    }

    public function createPage()
    {
        parent::checkContentManagerRights();

        $pagesManager = new PagesManager();
        $id = $pagesManager->createNewPage();

        $this->redirect('/admin/pages/edit?id=' . $id);
    }

    public function deletePage()
    {
        parent::checkContentManagerRights();

        if (!isset($_GET['id'])) {
            throw new NotFoundException();
        } else {
            $deletingPageId = $_GET['id'];
        }

        $pageManager = new PagesManager();
        $pageManager->deletePage($deletingPageId);

        return new JsonResponse(['result' => 'success']);
    }
}
