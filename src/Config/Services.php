<?php

use Controller\CartController;
use Controller\MainController;
use Controller\OrderController;
use Controller\UserController;
use Core\Container;
use Core\Logger;
use Psr\Log\LoggerInterface;
use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Repository\UserRepository;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\AuthenticationSessionService;
use Service\CartService;
use Service\OrderService;

return [
    LoggerInterface::class => function () {
        return new Logger();
    },
    AuthenticationServiceInterface::class => function (Container $container) {
        $userRepository = $container->get(UserRepository::class);
        $logger = $container->get(LoggerInterface::class);

        return new AuthenticationSessionService($userRepository, $logger);
    },
    CartService::class => function (Container $container) {
        $authService = $container->get(AuthenticationServiceInterface::class);
        $userProductRepository = $container->get(UserProductRepository::class);

        return new CartService($authService, $userProductRepository);
    },
    OrderService::class => function (Container $container) {
        $cartService = $container->get(CartService::class);
        $orderRepository = $container->get(OrderRepository::class);
        $orderProductRepository = $container->get(OrderProductRepository::class);
        $logger = $container->get(LoggerInterface::class);

        return new OrderService($cartService, $orderRepository, $orderProductRepository, $logger);
    },
    CartController::class => function (Container $container) {
        $authService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);

        return new CartController($authService, $cartService);
    },
    MainController::class => function (Container $container) {
        $authService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);
        $productRepository = $container->get(ProductRepository::class);

        return new MainController($authService, $cartService, $productRepository);
    },
    UserController::class => function (Container $container) {
        $authService = $container->get(AuthenticationServiceInterface::class);
        $userRepository = $container->get(UserRepository::class);

        return new UserController($authService, $userRepository);
    },
    OrderController::class => function (Container $container) {
        $authService = $container->get(AuthenticationServiceInterface::class);
        $cartService = $container->get(CartService::class);
        $orderService = $container->get(OrderService::class);

        return new OrderController($authService, $cartService, $orderService);
    }
];

