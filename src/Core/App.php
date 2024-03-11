<?php
class App
{
    public function run(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($uri === '/registrate') {
            $obj = new UserController();
            if ($method === 'GET') {
                $obj->getRegistrate();
            } elseif ($method === 'POST') {
                $obj->postRegistrate();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/login') {
            $obj = new UserController();
            if ($method === 'GET') {
                $obj->getLogin();
            } elseif ($method === 'POST') {
                $obj->postLogin();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/main') {
            if ($method === 'GET') {
                $obj = new MainController();
                $obj->getMain();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/add-product') {
            $obj = new CartController();
            if ($method === "POST"){
                $obj->addProduct();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/rm-product') {
            $obj = new CartController();
            if ($method === "POST"){
                $obj->removeProduct();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/cart') {
            $obj = new CartController();
            if ($method === 'GET') {
                $obj->getCart();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        } elseif ($uri === '/logout') {
            $obj = new UserController();
            if ($method === 'POST') {
                $obj->logout();
            } else {
                echo "$method не поддерживается для адреса $uri";
            }
        }
        else {
            require_once './../View/404.html';
        }
    }
}