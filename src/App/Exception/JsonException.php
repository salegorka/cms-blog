<?php

namespace App\Exception;

use Throwable;
use App\Interfaces\Renderable;

class JsonException extends \Exception implements Renderable
{
    public $error = [];

    public function __construct($error, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->error = $error;
        parent::__construct($message, $code, $previous);
    }

    public function render()
    {
        echo json_encode(['result' => 'fail', 'error' => $this->error]);
    }
}
