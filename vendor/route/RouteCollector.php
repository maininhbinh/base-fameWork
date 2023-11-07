<?php

namespace Vendor\route;


class RouteCollector
{
    private $routes = [];
    private $params = [];
    private $httpMethod = '';
    private $url = '';

    public function __construct()
    {
    }

    public function get($route, $handler)
    {
        $this->addRoute('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->addRoute('POST', $route, $handler);
    }

    public function any($route, $handler)
    {
        $this->addRoute('GET|POST', $route, $handler);
    }

    public function addRoute($httpMethod, $route, $handler)
    {
        $this->routes[] = [$httpMethod, $route, $handler];
    }

    public function dispatch($httpMethod, $url)
    {
        $this->httpMethod = $httpMethod;
        $this->url = $url;
    }

    public function map()
    {
        $checkRoute = false;
        $routes = $this->routes;
        // var_dump($items);
        foreach ($routes as $route) {
            list($httpMethod, $route, $handler) = $route;

            if (strpos($this->httpMethod, $httpMethod) === false) {
                continue;
            }

            if ($route === '*') {
                $checkRoute = true;
            } else if (strpos($route, '{') === false && strpos($route, '}') === false) {


                if (strcmp(strtolower(trim($this->url, '/')), strtolower(trim($route, '/'))) === 0) {
                    $checkRoute = true;
                } else {
                    continue;
                }
            } else {
                $routeParam = array_values(array_filter(explode('/', $route)));
                $requestParams = array_values(array_filter(explode('/', $this->url)));
                $pattern = preg_replace('/{[^}]+}/', '[^/]+', strtolower(trim($route, '/')));
                $match = preg_match(sprintf('~^%s$~', $pattern), strtolower(trim($this->url, '/')));

                if (count($routeParam) == count($requestParams) && $match) {
                    foreach ($routeParam as $index => $item) {
                        if (preg_match('/^{\w+}$/', $item)) {
                            $this->params[] = $requestParams[$index];
                            $checkRoute = true;
                        }
                    }
                } else {
                    continue;
                }
            }


            if ($checkRoute == true) {
                if (is_callable($handler)) {
                    call_user_func_array($handler, $this->params);
                    return;
                } else if (is_array($handler)) {
                    $this->compieRoute($handler);
                }
            } else {
                continue;
            }

            return;
        }
    }

    public function compieRoute($handler)
    {
        if (count($handler) !== 2) {
            die;
        }

        if (class_exists($handler[0])) {

            $object = new $handler[0];

            if (method_exists($object, $handler[1])) {
                call_user_func_array([$object, $handler[1]], $this->params);
            }
        }
    }

    public function run()
    {
        $this->map();
    }
}
