<?php

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Interfaces\Renderable;

class Application
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->initialize();
    }

    public function run()
    {
        $view = null;

        try {
            $view = $this->router->dispatch();
        }
        catch (\Exception $e) {
            $this->renderException($e);
        }

        if ($view instanceof Renderable) {
            $view->render();
        } else {
            echo $view;
        }
    }

    private function renderException($e)
    {
        if ($e instanceof Renderable) {
            $e->render();
        } else {
            echo "Код ошибки: " . ($e->getCode() === 0 ? 500 : $e->getCode()) . PHP_EOL;
            echo "Описание ошибки: " . $e->getMessage() . PHP_EOL;
        }
    }

    private function initialize()
    {
        $config = Config::getInstance();
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'mysql',
            'host' => $config->get('db.mysql.host'),
            'database' => $config->get('db.mysql.database'),
            'username' => $config->get('db.mysql.user'),
            'password' => $config->get('db.mysql.password'),
            'charset' => 'utf8',
            'prefix' => ''
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();


    }
}