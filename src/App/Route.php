<?php

namespace App;

class Route
{
    private $method;
    private $path;
    private $callback;

    public function __construct($method, $path, $callback)
    {
        $this->method = $method;
        $this->path = $path;
        $this->callback = $callback;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function match($method, $uri)
    {
        return ($this->method === $method && (1 === preg_match('/^' . str_replace(['*', '/'], ['\w+', '\/'], $this->path) . '$/', $uri)));
    }

    private function prepareCallback($callback) {

        if (is_callable($callback)) {
            return $callback;
        }
    }

    public function run($uri)
    {
        $callback = $this->prepareCallback($this->callback);

        $matches = [];
        preg_match('/^' . str_replace(['*', '/'], ['(\w+)', '\/'], $this->path) . '$/', $uri, $matches);
        array_shift($matches);
        return call_user_func_array($callback, $matches);

    }

}