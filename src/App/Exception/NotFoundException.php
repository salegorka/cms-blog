<?php

namespace App\Exception;

use App\Interfaces\Renderable;

class NotFoundException extends HttpException implements Renderable
{
    public function render()
    {
        echo "Ошибка 404: Page Not Found";
    }
}
