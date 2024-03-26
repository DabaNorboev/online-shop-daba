<?php

namespace Service;

use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\Repository;

class OrderService
{
    private OrderRepository $orderRepository;
    private OrderProductRepository $orderProductRepository;
    private CartService $cartService;
    public function __construct()
    {
        $this->orderProductRepository = new OrderProductRepository();
        $this->orderRepository = new OrderRepository();
        $this->cartService = new CartService();
    }

    public function create(int $userId, string $name, string $phoneNumber, string $address, string $comment): void
    {
        $pdo = Repository::getPdo();

        $pdo->beginTransaction();
        try {
            $this->orderRepository->create($userId, $name, $phoneNumber, $address, $comment);

            $orderId = $this->orderRepository->getOrderId();

            $userProducts = $this->cartService->getProducts();

            foreach ($userProducts as $userProduct) {

                $productId = $userProduct->getProduct()->getId();
                $quantity = $userProduct->getQuantity();

                $this->orderProductRepository->add($orderId,$productId,$quantity);
            }

            $this->cartService->clear();

            $pdo->commit();
        } catch (\Throwable $exception){
            $pdo->rollBack();
        }
    }
}