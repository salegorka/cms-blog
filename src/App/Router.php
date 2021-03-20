<?php

namespace App;

use App\Exception\NotFoundException;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes[] = new Route('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->routes[] = new Route('POST', $path, $callback);
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = parse_url($_SERVER['REQUEST_URI']);
        $path = $url['path'];

        foreach($this->routes as $route)
        {
            if ($route->match($method, $path))
            {
                return $route->run($path);
            }
        }

        throw new NotFoundException();
    }
}
