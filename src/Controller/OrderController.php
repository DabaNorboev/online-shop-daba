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

        $productsOfCart = $this->userProductModel->getCartProductsByUserId($userId);

        $totalPrice = $this->getTotalPrice($productsOfCart);

        require_once './../View/order.php';
    }

    public function getTotalPrice(array $products): int
    {
        $totalPrice = 0;

        if (!empty($products)){

            foreach($products as $product){
                $totalPrice += $product->getSum();
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
        $userId = $_SESSION['user_id'];

        $errors = $this->validateOrder($data,$userId);

        if (empty($errors)) {
            $name = $data['name'];
            $phoneNumber = $data['tel'];
            $address = $data['address'];
            $comment = $data['comment'];

            $this->orderModel->create($userId, $name, $phoneNumber, $address, $comment);

            $orderId = $this->orderModel->getOrderId();

            $productsOfCart = $this->userProductModel->getCartProductsByUserId($userId);

            foreach ($productsOfCart as $product) {
                $productId = $product->getId();
                $quantity = $product->getQuantity();
                $this->orderProductModel->add($orderId,$productId,$quantity);
            }

            $this->userProductModel->clearByUserId($userId);
        }

        header("Location: /order");
    }
    private function validateOrder(array $orderData, string $userId): array
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

        $productsOfCart = $this->userProductModel->getCartProductsByUserId($userId);

        if (empty($productsOfCart)){
            $errors['products-of-cart'] = 'Нельзя оформить заказ, т.к ваша корзина пуста';
        }

        return $errors;
    }
}