<?php
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/registrate') {
    require_once './../Controller/UserController.php';
    $obj = new UserController();
    if ($method === 'GET') {
        $obj->getRegistrate();
    } elseif ($method === 'POST') {
        $obj->postRegistrate();
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/login') {
    require_once './../Controller/UserController.php';
    $obj = new UserController();
    if ($method === 'GET') {
        $obj->getLogin();
    } elseif ($method === 'POST') {
        $obj->postLogin();
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/main') {
    require_once './../Controller/MainController.php';
    $obj = new MainController();
    if ($method === 'GET') {
        $obj->getMain();
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/add-product') {
    require_once './../Controller/ProductController.php';
    $obj = new ProductController();
    if ($method === 'GET') {
        $obj->getAddProduct();
    } elseif ($method === 'POST') {
        $obj->postAddProduct();
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
}
else {
    require_once './../View/404.html';
}
