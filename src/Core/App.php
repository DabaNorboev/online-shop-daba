<?php

namespace Core;

use Psr\Log\LoggerInterface;
use Request\Request;

class App
{
    private Container $container;
    private array $routes = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];

        try {
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

                    $obj = $this->container->get($class);
                    $obj->$method($request);

                } else {
                    echo "$method не поддерживается для $uri";
                }
            } else {
                require_once './../View/404.html';
            }
        }
        Catch (\Throwable $exception){
            $logger = $this->container->get(LoggerInterface::class);

            $data = [
                'message' => 'Сообщение об ошибке: ' . $exception->getMessage(),
                'code' => 'Код: ' . $exception->getCode(),
                'file' => 'Файл: ' . $exception->getFile(),
                'line' => 'Строка: ' . $exception->getLine(),
                'stackTrace' => 'Стэк: ' . $exception->getTraceAsString(),
                'details' => 'Подробная информация: ' . $exception->__toString()
            ];

            $logger->error("code execution error\n", $data);

            require_once './../View/500.html';
        }

    }

    public function getClass(string $className)
    {

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