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
    public function addProduct(array $data): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];

        $errors = $this->validateProduct($data);

        if (empty($errors)) {
            $productId = $data['product_id'];
            $quantity = 1;

            $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);

            if (empty($userProduct)) {
                $this->userProductModel->add($userId, $productId, $quantity);
            }
            else {
                $this->userProductModel->updateQuantityPlus($userId, $productId, $quantity);
            }
        }

        header("Location: /main");
    }

    public function removeProduct(array $data): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $errors = $this->validateProduct($data);

        if (empty($errors)) {
            $productId = $data['product_id'];
            $quantity = 1;

            $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);

            if (!empty($userProduct)) {
                if ($userProduct->getQuantity() === 1) {
                    $this->userProductModel->remove($userId, $productId);
                } elseif ($userProduct->getQuantity() !== 0){
                    $this->userProductModel->updateQuantityMinus($userId, $productId, $quantity);
                }
            }
        }

        header("Location: /main");
    }
    private function validateProduct(array $data): array
    {
        $errors = [];

        foreach ($data as $key=>$value)
        {
            if (isset($value)){
                $values[$key] = $value;
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif ($key === 'product_id') {
                    if (ctype_digit($value)) {
                        $productById = $this->productModel->getOneById($value);
                        if (empty($productById)) {
                            $errors[$key] = 'Продукта с таким id не существует';
                        }
                    } else {
                        $errors[$key] = 'Некорректный формат id продукта';
                    }
                }elseif ($key === 'quantity') {
                    if (!ctype_digit($value)) {
                        $errors[$key] = 'Некорректный формат количества продукта';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
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

        $productsOfCart = $this->userProductModel->getCartProductsByUserId($userId);

        $totalPrice = $this->getTotalPrice($productsOfCart);

        require_once './../View/cart.php';
    }
    private function getTotalPrice(array $products): int
    {
        $totalPrice = 0;

        if (!empty($products)){

            foreach($products as $product){
                $totalPrice += $product->getSum();
            }
        }

        return $totalPrice;
    }
    public function clearCart(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }

        $userId = $_SESSION['user_id'];

        $this->userProductModel->clearByUserId($userId);

        header("Location: /main");
    }
    public function clearProduct(array $data): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $errors = $this->validateProduct($data);

        if (empty($errors)){
            $productId = $data['product_id'];

            $this->userProductModel->clearProductByUserIdProductId($userId,$productId);
        }

        header("Location: /cart");
    }
}