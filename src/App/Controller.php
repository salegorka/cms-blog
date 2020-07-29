<?php

namespace App;

use App\View\View;
use App\Model\Book;

class Controller
{
    private $activeViewName;

    public function __construct($activeViewName)
    {
        $this->activeViewName = $activeViewName;
    }

    public function render() {
        return new View($this->activeViewName, $this->loadData());
    }

    public function loadData()
    {
        if ($this->activeViewName == 'index') {
            return ['title' => 'IndexPage'];
        } elseif ($this->activeViewName == 'books') {
            $books = Book::all();
            return $books;
        }
    }
}