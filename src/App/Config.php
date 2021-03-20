<?php

namespace App;

final class Config
{
    private static $instance;
    private $configs = [];

    private function __construct()
    {
        $this->configs['db'] = include $_SERVER['DOCUMENT_ROOT'] . '/settings/db.php';
        $this->configs['mainSettings'] = readJsonFile($_SERVER['DOCUMENT_ROOT'] . '/settings/mainSettings.json');
    }

    public static function getInstance() : Config
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get($config, $default = null)
    {
        return array_get($this->configs, $config, $default);
    }


    public function updateMainArticlesCount($value)
    {
        $this->configs['mainSettings']['mainPageArticleCount'] = $value;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/settings/mainSettings.json', json_encode($this->configs['mainSettings']));
    }

    public function updateMenu($menu)
    {
        $this->configs['mainSettings']['menu'] = $menu;
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/settings/mainSettings.json', json_encode($this->configs['mainSettings']));
    }
}
