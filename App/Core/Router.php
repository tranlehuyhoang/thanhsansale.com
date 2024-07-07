<?php

namespace App\Core;

class Router
{
    protected $routes = [];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }
    // put 
    public function put($uri, $action)
    {
        $this->routes['PUT'][$uri] = $action;
    }
    // delete
    public function delete($uri, $action)
    {
        $this->routes['DELETE'][$uri] = $action;
    }

    public function run()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        // check uri if have /?xxxxxxx to remove
        if (strpos($uri, '?') !== false) {
            $uri = explode('?', $uri);
            $uri = $uri[0];
        }

        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $route => $action) {
                // Replace any dynamic route parameters with a regular expression
                $route = preg_replace('/{[^}]+}/', '([^/]+)', $route);

                // Check if route matches the current request URI
                if (preg_match('#^' . $route . '$#', $uri, $matches)) {
                    // Remove the first match (the entire matched string)
                    array_shift($matches);

                    // Split the action into controller and method
                    $action = explode('@', $action);
                    $controller = 'App\\Controllers\\' . $action[0];
                    $method = $action[1];

                    // Call the controller action with the extracted route parameters
                    call_user_func_array([new $controller, $method], $matches);
                }
            }
        } else {
            $controller = 'App\\Controllers\\' . 'ErrorController';
            $method = 'PageNotFound';
            call_user_func_array([new $controller, $method], []);
            return;
        }
    }

    public function loadRoutes()
    {
        require_once '../App/Routes/Web.php';
    }
}
