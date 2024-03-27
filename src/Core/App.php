<?php

namespace Core;

use Controller\CartController;
use Controller\MainController;
use Controller\OrderController;
use Controller\UserController;
use Request\ChangeProductRequest;
use Request\LoginRequest;
use Request\OrderRequest;
use Request\RegistrationRequest;
use Request\Request;
use Service\Authentication\AuthenticationSessionService;
use Service\CartService;

class App
{
    private array $routes = [];
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$uri])) {
            $method = $_SERVER['REQUEST_METHOD'];
            $routeMethod = $this->routes[$uri];

            if (isset($routeMethod[$method])) {
                $handler = $routeMethod[$method];

                $class = $handler['class'];
                $method = $handler['method'];
                $requestClass = Request::class;

                if (isset($handler['request-class'])){
                    $requestClass = $handler['request-class'];
                }

                $request = new $requestClass($method, $uri, headers_list(), $_POST);

                $authService = new AuthenticationSessionService();
                $cartService = new CartService();

                $obj = new $class($authService, $cartService);
                $obj->$method($request);

            } else {
                echo "$method не поддерживается для $uri";
            }
        } else {
            require_once './../View/404.html';
        }
    }

    public function get(string $routeName, string $class, string $method, string $requestClass = null): void
    {
        $this->routes[$routeName]['GET'] = [
            'class' => $class,
            'method' => $method,
            'request-class' => $requestClass,
        ];
    }

    public function post(string $routeName, string $class, string $method, string $requestClass = null): void
    {
        $this->routes[$routeName]['POST'] = [
            'class' => $class,
            'method' => $method,
            'request-class' => $requestClass,
        ];
    }

//    public function addRoute(string $routeName, string $class, string $method, string $requestMethod, string $requestClass = null): void
//    {
//        $this->routes[$routeName][$requestMethod] = [
//            'class' => $class,
//            'method' => $method,
//            'request-class' => $requestClass,
//        ];
//    }
}