<?php

namespace Controller;

use Model\Order;
use Model\OrderProduct;
use Model\Product;
use Model\UserProduct;

class OrderController
{
    private Order $orderModel;
    private OrderProduct $orderProductModel;
    private UserProduct $userProductModel;
    private Product $productModel;
    public function __construct()
    {
        $this->orderModel = new Order();
        $this->userProductModel = new UserProduct();
        $this->productModel = new Product();
        $this->orderProductModel = new OrderProduct();
    }

    public function getOrder()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $productsOfCart = $this->getProductsOfCart($userId);
        $totalPrice = $this->getTotalPrice($productsOfCart);

        require_once './../View/order.php';
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

                        $productOfCart['id'] = $product['id'];
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

    public function postOrder(array $data)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $errors = $this->validateOrder($data);

        header("Location: /order");
        if (empty($errors)) {
            $userId = $_SESSION['user_id'];

            $productsOfCart = $this->getProductsOfCart($userId);

            if (!empty($productsOfCart)) {
                $name = $data['name'];
                $phoneNumber = $data['tel'];
                $address = $data['address'];
                $comment = $data['comment'];

                $this->orderModel->create($userId, $name, $phoneNumber, $address, $comment);


                $orderId = $this->orderModel->getOrderId();

                foreach ($productsOfCart as $product) {
                    $productId = $product['id'];
                    $quantity = $product['quantity'];
                    $this->orderProductModel->add($orderId,$productId,$quantity);
                }

                $this->userProductModel->clearByUserId($userId);
            } else {
                header("Location: /main");
            }
        }
    }
    private function validateOrder(array $orderData): array
    {
        $errors = [];

        foreach ($orderData as $key=> $value)
        {
            if (isset($value)){
                if (empty($value)) {
                    if ($key !== 'comment'){
                        $errors['comment'] = "Это поле не должно быть пустым";
                    }
                }elseif ($key === 'name'){
                    if (mb_strlen($value, 'UTF-8') < 2) {
                        $errors['name'] = 'Минимально допустимая длина имени - 2 символа';
                    }
                }elseif ($key === 'tel'){
                    if (ctype_digit($value)) {
                        if (mb_strlen($value, 'UTF-8') !== 11) {
                            $errors['tel'] = 'Количество цифр в номере телефона не соответствует образцу';
                        }
                    } else {
                        $errors['tel'] = 'Номер телефона может состоять только из цифр, посмотрите образец';
                    }
                }elseif ($key === 'address'){
                    if (mb_strlen($value, 'UTF-8') < 5) {
                        $errors['address'] = 'Минимально допустимая длина адреса - 5 символов';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }
        return $errors;
    }
}