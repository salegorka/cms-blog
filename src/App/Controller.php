<?php

namespace App;

use App\View\View;
use App\Model\Book;

class Controller
{
    private $activeViewName;
    private $data;

    public function __construct($activeViewName, $data = NULL)
    {
        $this->activeViewName = $activeViewName;
        $this->data = $data;
    }

    public function render()
    {
        return new View($this->activeViewName, $this->data);
    }

}