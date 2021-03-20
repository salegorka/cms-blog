<?php

namespace App\View;

use App\Interfaces\Renderable;

class View implements Renderable
{
    private $viewName;
    private $data;

    public function __construct($viewName, $data = null)
    {
        $this->viewName = $viewName;
        $this->data = $data;
    }

    public function render()
    {
        $pathToTemplate = VIEW_DIR . '/' . str_replace('.', '/', $this->viewName) . '.php';
        $data = $this->data;
        require $pathToTemplate;
    }
}
