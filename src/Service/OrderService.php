<?php

namespace Service;

use Psr\Log\LoggerInterface;
use Repository\OrderProductRepository;
use Repository\OrderRepository;
use Repository\Repository;

class OrderService
{
    private OrderRepository $orderRepository;

    private OrderProductRepository $orderProductRepository;

    private CartService $cartService;

    private LoggerInterface $logger;
    public function __construct(CartService $cartService, OrderRepository $orderRepository, OrderProductRepository $orderProductRepository, LoggerInterface $logger)
    {
        $this->orderProductRepository = $orderProductRepository;
        $this->orderRepository = $orderRepository;
        $this->cartService = $cartService;
        $this->logger = $logger;
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

            $data = [
                'user-id' => "user_id: " . $userId,
                'order-id' => "order_id: " . $orderId
            ];

            $this->logger->info("Order is processed\n", $data);

        } catch (\Throwable $exception){
            $data = [
                'message' => 'Сообщение об ошибке: ' . $exception->getMessage(),
                'code' => 'Код: ' . $exception->getCode(),
                'file' => 'Файл: ' . $exception->getFile(),
                'line' => 'Строка: ' . $exception->getLine(),
                'stackTrace' => 'Стэк: ' . $exception->getTraceAsString(),
                'details' => 'Подробная информация: ' . $exception->__toString(),
                'userId' => 'Идентификатор пользователя' . $userId
            ];

            $this->logger->error("ORDER ERROR\n", $data);

            require_once './../View/500.html';

            $pdo->rollBack();
        }
    }
}