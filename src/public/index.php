<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dabanorboev\MyPack\Autoloader;
use Dabanorboev\MyPack\App;
use Dabanorboev\MyPack\Container;

use Controller\UserController;
use Controller\MainController;
use Controller\CartController;
use Controller\OrderController;

use Request\RegistrationRequest;
use Request\LoginRequest;
use Request\OrderRequest;
use Request\ChangeProductRequest;

require_once '../../vendor/dabanorboev/my-pack/Core/Autoloader.php';

Autoloader::register(dirname(__DIR__));

$services = include './../Config/Services.php';

$container = new Container($services);

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
//order
$app->post('/order',OrderController::class, 'postOrder', OrderRequest::class);

$app->run();
