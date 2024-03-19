<?php

namespace Controller;

use Repository\OrderRepository;
use Repository\OrderProductRepository;
use Repository\ProductRepository;
use Repository\UserProductRepository;
use Request\OrderRequest;

class OrderController
{
    private OrderRepository $orderRepository;
    private OrderProductRepository $orderProductRepository;
    private UserProductRepository $userProductRepository;
    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
        $this->userProductRepository = new UserProductRepository();
        $this->orderProductRepository = new OrderProductRepository();
    }

    public function getOrder(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $userProducts = $this->userProductRepository->getAllByUserId($userId);

        $totalPrice = $this->getTotalPrice($userProducts);

        require_once './../View/order.php';
    }

    public function getTotalPrice(array $userProducts): int
    {
        $totalPrice = 0;

        if (!empty($userProducts)){

            foreach($userProducts as $userProduct){
                $price = $userProduct->getProduct()->getPrice();

                $totalPrice += $price * $userProduct->getQuantity();
            }
        }

        return $totalPrice;
    }

    public function postOrder(OrderRequest $request): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $errors = $request->validate($userId);

        if (empty($errors)) {
            $name = $request->getName();
            $phoneNumber = $request->getPhoneNumber();
            $address = $request->getAddress();
            $comment = $request->getComment();

            $this->orderRepository->create($userId, $name, $phoneNumber, $address, $comment);

            $orderId = $this->orderRepository->getOrderId();

            $userProducts = $this->userProductRepository->getAllByUserId($userId);

            foreach ($userProducts as $userProduct) {

                $productId = $userProduct->getProduct()->getId();
                $quantity = $userProduct->getQuantity();

                $this->orderProductRepository->add($orderId,$productId,$quantity);
            }

            $this->userProductRepository->clearByUserId($userId);
        }

        header("Location: /order");
    }
}