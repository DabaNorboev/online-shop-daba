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

class App
{
    private array $routes = [
        '/registration' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistration',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postRegistration',
                'request-class' => RegistrationRequest::class
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postLogin',
                'request-class' => LoginRequest::class
            ]
        ],
        '/logout' => [
            'POST' => [
                'class' => UserController::class,
                'method' => 'logout',
            ]
        ],
        '/main' => [
            'GET' => [
                'class' => MainController::class,
                'method' => 'getMain',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ]
        ],
        '/add-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'addProduct',
                'request-class' => ChangeProductRequest::class
            ]
        ],
        '/rm-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'removeProduct',
                'request-class' => ChangeProductRequest::class
            ]
        ],
        '/clear-product' => [
            "POST" => [
                'class' => CartController::class,
                'method' => 'clearProduct',
                'request-class' => ChangeProductRequest::class
            ]
        ],
        '/clear-cart' => [
            "POST" => [
                'class' => CartController::class,
                'method' => 'clearCart',
            ]
        ],
        '/order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getOrder',
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'postOrder',
                'request-class' => OrderRequest::class
            ]
        ],
    ];
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

                if (isset($handler['request-class'])){
                    $requestClass = $handler['request-class'];
                    $request = new $requestClass($method, $uri, headers_list(), $_POST);

                    $obj = new $class;
                    $obj->$method($request);

                } else {
                    $obj = new $class;
                    $obj->$method();
                }

            } else {
                echo "$method не поддерживается для $uri";
            }
        } else {
            require_once './../View/404.html';
        }
    }
}