<?php

namespace App;

final class Config
{
    private static $instance;
    private $configs = [];

    private function __construct()
    {
        $this->configs['db'] = include $_SERVER['DOCUMENT_ROOT'] . '/settings/db.php';
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
        return array_get($this->configs, $config);
    }

}