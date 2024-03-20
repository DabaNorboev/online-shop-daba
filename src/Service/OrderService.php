<?php

namespace Service;

use Controller\Admin\OrderController;
use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\UserProductRepository;

class OrderService
{
    private OrderRepository $orderRepository;
    private UserProductRepository $userProductRepository;
    private OrderProductRepository $orderProductRepository;

    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
        $this->orderProductRepository = new OrderProductRepository();
        $this->orderRepository = new OrderRepository();
        }

    public function create(int $userId, string $name, string $phoneNumber, string $address, string $comment): void
    {
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
}