<?php

namespace App\Exception;

use App\Interfaces\Renderable;

class NotFoundException extends HttpException implements Renderable
{
    public function render()
    {
        http_response_code(404);
        echo "Ошибка 404: Page Not Found";
    }
}
