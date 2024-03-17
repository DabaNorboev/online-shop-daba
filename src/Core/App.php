<?php

namespace Core;

use Controller\CartController;
use Controller\MainController;
use Controller\OrderController;
use Controller\UserController;
use Repository\OrderRepository;

class App
{
    private array $routes = [
        '/registrate' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postRegistrate',
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
            ]
        ],
        '/main' => [
            'GET' => [
                'class' => MainController::class,
                'method' => 'getMain',
            ]
        ],
        '/logout' => [
            'POST' => [
                'class' => UserController::class,
                'method' => 'logout'
            ]
        ],
        '/add-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'addProduct',
            ]
        ],
        '/rm-product' => [
            'POST' => [
                'class' => CartController::class,
                'method' => 'removeProduct',
            ]
        ],
        '/cart' => [
            'GET' => [
                'class' => CartController::class,
                'method' => 'getCart',
            ]
        ],
        '/order' => [
            'GET' => [
                'class' => OrderController::class,
                'method' => 'getOrder'
            ],
            'POST' => [
                'class' => OrderController::class,
                'method' => 'postOrder'
            ]
        ],
        '/clear-cart' => [
            "POST" => [
                'class' => CartController::class,
                'method' => 'clearCart'
            ]
        ],
        '/clear-product' => [
            "POST" => [
                'class' => CartController::class,
                'method' => 'clearProduct'
            ]
        ]
    ];
    public function run()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (isset($this->routes[$uri])) {
            $method = $_SERVER['REQUEST_METHOD'];
            $routeMethod = $this->routes[$uri];

            if (isset($routeMethod[$method])) {
                $handler = $routeMethod[$method];

                $class = $handler['class'];
                $method = $handler['method'];

                $obj = new $class;
                $obj->$method($_POST);
            } else {
                echo "$method не поддерживается для $uri";
            }
        } else {
            require_once './../View/404.html';
        }
    }
}