<?php

namespace App\Exception;

use App\Interfaces\Renderable;

class BadAuthorizedException extends HttpException implements Renderable
{
    public function render()
    {
        http_response_code(401);
        echo "Ошибка, недостаточно прав для посещения страницы";
    }
}