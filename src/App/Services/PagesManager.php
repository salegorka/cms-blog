<?php

namespace App\Services;

use App\Model\Page;
use App\Config;

class PagesManager {

    public function loadPageContent($id)
    {
        $page = Page::find($id);
        return $page->content;
    }

    public function loadAllPages()
    {
        $pages = Page::all();
        $pages = $pages->toArray();
        return $pages;
    }

    public function updatePage($id, $data)
    {

        // Изменение записи в БД

        try {
            $page = Page::findOrFail($id);
        } catch (\Exception $e) {
            return "Произошла ошибка. Указанной страницы не найдено.";
        }

        $page->content = $data['content'];
        $page->save();

        // Изменение обьекта меню

        $config = Config::getInstance();
        $menu = $config->get('mainSettings.menu');

        foreach($menu as &$item) {
            if ($item['id'] == $id) {
                $item['link'] = $data['link'];
                $item['name'] = $data['name'];
            }
        }

        $config->updateMenu($menu);

        return "Страница успешно изменена";

    }

    public function createNewPage()
    {

        $page = new Page();

        $page->content = '';
        $page->save();

        $id = $page->id;

        $config = Config::getInstance();
        $menu = $config->get('mainSettings.menu');

        $menu[] = ['name' => 'Новая страница', 'link' => 'page' . $id, 'id' => $id];
        $config->updateMenu($menu);

        return $id;

    }

    public function deletePage($id)
    {

        Page::destroy($id);

        $config = Config::getInstance();
        $menu = $config->get('mainSettings.menu');

        foreach($menu as $key => $item) {
            if ($item['id'] == $id) {
                unset($menu[$key]);
            }
        }

        $config->updateMenu($menu);

        return 0;

    }
}