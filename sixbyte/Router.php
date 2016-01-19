<?php

namespace Sixbyte;

use Closure;

/**
 * 路由类
 */
class Router
{

    protected $routes = [];

    protected $notFoundHandle;

    public function GET(string $uri, array $action)
    {
        $this->set('GET', $uri, $action);
    }

    public function POST(string $uri, array $action)
    {
        $this->set('POST', $uri, $action);
    }

    public function PUT(string $uri, array $action)
    {
        $this->set('PUT', $uri, $action);
    }

    public function DELETE(string $uri, array $action)
    {
        $this->set('DELETE', $uri, $action);
    }

    public function PATCH(string $uri, array $action)
    {
        $this->set('PATCH', $uri, $action);
    }

    protected function set(string $method, string $uri, array $action)
    {
        if (!isset($action['use'])) {
            trigger_error("the `use` is must in route");
        }
        $this->routes[$this->key($method, $uri)] = $action;
    }

    public function routes()
    {
        return $this->routes;
    }

    public function math()
    {
        // method
        $method = $_SERVER['REQUEST_METHOD'];
        if ('POST' === $method) {
            if ($method = $_SERVER('X-HTTP-METHOD-OVERRIDE')) {
                $method = strtoupper($method);
            }
        }
        $query_string = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
        // path_info
        $path_info = '/' . trim(str_replace('?' . $query_string, '', $_SERVER['REQUEST_URI']), '/');
        $key       = $this->key($method, $path_info);
        if (isset($this->routes[$key])) {
            return $this->routes[$key];
        }

        call_user_func($this->notFoundHandle);

    }

    protected function key(string $method, string $path_info)
    {
        return $method . ':' . $path_info;
    }

    public function setNotFoundHandle(Closure $closure)
    {
        $this->notFoundHandle = $closure;
    }
}
