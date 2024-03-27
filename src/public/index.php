<?php

use Core\Autoloader;
use Core\App;
use Core\Container;

use Controller\UserController;
use Controller\MainController;
use Controller\CartController;
use Controller\OrderController;

use Request\RegistrationRequest;
use Request\LoginRequest;
use Request\OrderRequest;
use Request\ChangeProductRequest;

use Service\Authentication\AuthenticationSessionService;
use Service\CartService;
use Service\OrderService;

use Repository\ProductRepository;
use Repository\UserRepository;

require_once './../Core/Autoloader.php';

Autoloader::register(dirname(__DIR__));

$container = new Container();

$container->set(CartController::class, function () {
    $authService = new AuthenticationSessionService();
    $cartService = new CartService($authService);

    return new CartController($authService, $cartService);
});

$container->set(MainController::class, function () {
    $authService = new AuthenticationSessionService();
    $cartService = new CartService($authService);
    $productRepository = new ProductRepository();

    return new MainController($authService, $cartService, $productRepository);
});

$container->set(UserController::class, function () {
    $authService = new AuthenticationSessionService();
    $userRepository = new UserRepository();

    return new UserController($authService, $userRepository);
});

$container->set(OrderController::class, function () {
    $authService = new AuthenticationSessionService();
    $cartService = new CartService($authService);
    $orderService = new OrderService($authService);

    return new OrderController($authService, $cartService, $orderService);
});

$app = new App($container);

$app->get('/registration',UserController::class, 'getRegistration');
$app->get('/login',UserController::class, 'getLogin');
$app->get('/main',MainController::class, 'getMain');
$app->get('/cart',CartController::class, 'getCart');
$app->get('/order',OrderController::class, 'getOrder');

//user
$app->post('/registration',UserController::class, 'postRegistration', RegistrationRequest::class);
$app->post('/login',UserController::class, 'postLogin', LoginRequest::class);
$app->post('/logout',UserController::class, 'logout');
//cart
$app->post('/add-product', CartController::class, 'addProduct', ChangeProductRequest::class);
$app->post('/rm-product', CartController::class, 'removeProduct', ChangeProductRequest::class);
$app->post('/clear-product', CartController::class, 'clearProduct', ChangeProductRequest::class);
$app->post('/clear-cart', CartController::class, 'clearCart', ChangeProductRequest::class);
$app->post('/rm-product', CartController::class, 'removeProduct', ChangeProductRequest::class);
//order
$app->post('/order',OrderController::class, 'postOrder', OrderRequest::class);

$app->run();
