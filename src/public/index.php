<?php
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/registrate') {
    if ($method === 'GET') {
        require_once 'registrate.php';
    } elseif ($method === 'POST') {
        require_once 'post_registrate.php';
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/login') {
    if ($method === 'GET') {
        require_once 'login.php';
    } elseif ($method === 'POST') {
        require_once 'post_login.php';
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/main') {
    if ($method === 'GET') {
        require_once 'main.php';
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
} elseif ($uri === '/add-product') {
    if ($method === 'GET') {
        require_once 'add-product.php';
    } elseif ($method === 'POST') {
        require_once 'post_add-product.php';
    } else {
        echo "$method не поддерживается для адреса $uri";
    }
}
else {
    require_once '404.html';
}
