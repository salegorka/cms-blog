<?php

namespace App\View;

use App\Interfaces\Renderable;

class JsonResponse implements Renderable
{
    private $data;

    public function __construct($data = null)
    {
        $this->data = $data;
    }

    public function render()
    {
        echo json_encode($this->data);
    }
}
