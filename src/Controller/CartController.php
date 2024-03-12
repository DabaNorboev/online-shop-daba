<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class CartController
{
    private Product $productModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }
    public function addProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);

        $errors = $this->validateAddProduct($userProduct);

        if (empty($errors)) {

            if (empty($userProduct)) {
                $this->userProductModel->add($userId, $productId, $quantity);
            }
            else {
                $this->userProductModel->updateQuantityPlus($userId, $productId, $quantity);
            }
        }

        header("Location: /main");

    }
    private function validateAddProduct(array $userProduct): array
    {
        $errors = [];

        //допустим, что каждый товар имеется в количетсве 20 шт
        if (!empty($userProduct)){
            if ($userProduct['quantity'] === 20){
                $errors['quantity'] = 'Товар закончился';
            }
        }
        return $errors;
    }

    public function removeProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];
        $productId = $_POST['product_id'];
        $quantity = 1;

        $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);

        $errors = $this->validateRemoveProduct($userProduct);
        if (empty($errors))
        if (!empty($userProduct)) {
            if ($userProduct['quantity'] === 1) {
                $this->userProductModel->remove($userId, $productId);
            } elseif ($userProduct['quantity'] !== 0){
                $this->userProductModel->updateQuantityMinus($userId, $productId, $quantity);
            }
        }
        header("Location: /main");
    }
    private function validateRemoveProduct(array $userProduct): array
    {
        $errors = [];
        if (empty($userProduct)){
            $errors['quantity'] = 'Товара нет в корзине';
        }

    return $errors;
    }
    public function getCart(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];
        $productsOfCart = $this->getProductsOfCart($userId);
        $totalPrice = $this->getTotalPrice($productsOfCart);
        require_once './../View/cart.php';
    }
    public function getProductsOfCart($userId): array
    {
        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $productsOfCart = [];
        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productOfCart = [];
                foreach ($products as $product) {
                    if ($product['id'] === $userProduct['product_id']) {
                        $productOfCart['name'] = $product['name'];
                        $productOfCart['img_url'] = $product['img_url'];
                        $productOfCart['price'] = $product['price'];
                        $productOfCart['quantity'] = $userProduct['quantity'];
                        $productOfCart['sum'] = $productOfCart['quantity'] * $productOfCart['price'];
                    }
                }
                $productsOfCart[] = $productOfCart;
            }
        }

        return $productsOfCart;
    }
    public function getTotalPrice(array $products): int
    {
        $totalPrice = 0;
        if (!empty($products)){
            foreach($products as $product){
                $totalPrice += $product['sum'];
            }
        }
        return $totalPrice;
    }
}