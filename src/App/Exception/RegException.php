<?php

namespace App\Exception;

use Throwable;

class RegException extends \Exception {

    public $error = [];

    public function __construct($error, $message = "", $code = 0, Throwable $previous = null)
    {
        $this->error = $error;
        parent::__construct($message, $code, $previous);
    }

}