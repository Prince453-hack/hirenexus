<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    public function registerRoute($method, $uri, $action)
    {

        if (strpos($action, '@') === false) {
            throw new \InvalidArgumentException("Action must be in the format 'Controller@method'");
        }

        list($controller, $controllerMethod) = explode('@', $action);

        if (empty($controller) || empty($controllerMethod)) {
            throw new \InvalidArgumentException("Controller and method must not be empty");
        }

        $this->routes[] = [
            "method" => $method,
            "uri" => $uri,
            "controller" => $controller,
            "controllerMethod" => $controllerMethod,
        ];
    }

    public function get($uri, $controller)
    {
        $this->registerRoute("GET", $uri, $controller);
    }

    public function post($uri, $controller)
    {
        $this->registerRoute("POST", $uri, $controller);
    }

    public function put($uri, $controller)
    {
        $this->registerRoute("PUT", $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->registerRoute("DELETE", $uri, $controller);
    }

    public function route($uri)
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $found = false;

        foreach ($this->routes as $route) {
            $uriSegments = explode('/', trim($uri, '/'));
            $routeSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            if (count($uriSegments) === count($routeSegments) && strtoupper($route['method']) === $requestMethod) {
                $params = [];
                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    if ($routeSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeSegments[$i])) {
                        $match = false;
                        break;
                    }

                    if (preg_match('/\{(.+?)\}/', $routeSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }

                if ($match) {
                    $controller = 'App\\Controllers\\' . $route["controller"];
                    $controllerMethod = $route["controllerMethod"];

                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    $found = true;
                    break;
                }
            }
        }

        if (!$found) {
            ErrorController::notFound();
        }
    }
}
