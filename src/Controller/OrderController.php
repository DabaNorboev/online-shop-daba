<?php

namespace Controller;

use Repository\OrderRepository;
use Repository\OrderProductRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;

class OrderController
{
    private OrderRepository $orderRepository;
    private OrderProductRepository $orderProductRepository;
    private UserProductRepository $userProductRepository;
    private ProductRepository $productRepository;
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->userProductRepository = new UserProductRepository();
        $this->productRepository = new ProductRepository();
        $this->orderProductRepository = new OrderProductRepository();
    }

    public function getOrder(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $productsOfCart = $this->userProductRepository->getAllByUserId($userId);

        $totalPrice = $this->getTotalPrice($productsOfCart);

        require_once './../View/order.php';
    }

    public function getTotalPrice(array $products): int
    {
        $totalPrice = 0;

        if (!empty($products)){

            foreach($products as $product){
                $totalPrice += $product->getProduct()->getPrice()*$product->getQuantity();
            }
        }

        return $totalPrice;
    }

    public function postOrder(array $data): void
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

            $this->orderRepository->create($userId, $name, $phoneNumber, $address, $comment);

            $orderId = $this->orderRepository->getOrderId();

            $productsOfCart = $this->userProductRepository->getAllByUserId($userId);

            foreach ($productsOfCart as $product) {
                $productId = $product->getProduct()->getId();
                $quantity = $product->getQuantity();
                $this->orderProductRepository->add($orderId,$productId,$quantity);
            }

            $this->userProductRepository->clearByUserId($userId);
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

        $productsOfCart = $this->userProductRepository->getAllByUserId($userId);

        if (empty($productsOfCart)){
            $errors['products-of-cart'] = 'Нельзя оформить заказ, т.к ваша корзина пуста';
        }

        return $errors;
    }
}