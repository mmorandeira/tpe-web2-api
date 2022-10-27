<?php

namespace Moran;

class Route
{
    private $url;
    private $verb;
    private $controller;
    private $method;
    private $params;

    public function __construct($url, $verb, $controller, $method)
    {
        $this->url = $url;
        $this->verb = $verb;
        $this->controller = $controller;
        $this->method = $method;
        $this->params = [
            'pathParams' => [],
            'queryParams' => [],
        ];
    }

    public function match($url, $verb)
    {
        if ($this->verb != $verb)
            return false;

        $partsURL = explode("/", trim($url, '/'));

        $lastPart = end($partsURL);
        $partsURL[count($partsURL) - 1] = explode("?", $lastPart)[0];
        $queryParams = explode("&", explode("?", $lastPart)[1]);

        $associativeQueryParams = [];
        foreach ($queryParams as $param) {
            $param = explode("=", $param);
            $associativeQueryParams[$param[0]] = $param[1];
        }
        $this->params['queryParams'] = $associativeQueryParams;

        $partsRoute = explode("/", trim($this->url, '/'));
        if (count($partsRoute) != count($partsURL))
            return false;

        foreach ($partsRoute as $key => $part) {
            if ($part[0] != ":") {
                if ($part != $partsURL[$key])
                    return false;
            } //es un parametro
            else {
                $this->params['pathParams'][$part] = $partsURL[$key];
            }
        }
        return true;
    }

    public function run()
    {
        $controller = $this->controller;
        $method = $this->method;
        $params = $this->params;

        (new $controller())->$method($params);
    }
}

class Router
{

    private $routeTable = [];
    private $defaultRoute;

    public function __construct()
    {
        $this->defaultRoute = null;
    }

    public function route($url, $verb)
    {

        foreach ($this->routerTable as $route) {
            if ($route->match($url, $verb)) {
                $route->run();
                return;
            }
        }

        if ($this->defaultRoute != null)
            $this->defaultRoute->run();
    }

    public function addRoute($url, $verb, $controller, $method)
    {
        $this->routerTable[] = new Route($url, $verb, $controller, $method);
    }

    public function setDefaultRoute($controller, $method)
    {
        $this->defaultRoute = new Route("", "", $controller, $method);
    }
}
