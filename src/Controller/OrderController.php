<?php

namespace Controller;

use Repository\UserProductRepository;
use Request\OrderRequest;
use Service\UserService;
use Service\CartService;
use Service\OrderService;

class OrderController
{
    private UserProductRepository $userProductRepository;

    private OrderService $orderService;

    private CartService $cartService;

    private UserService $userService;

    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->userService = new UserService();
    }

    public function getOrder(): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $userProducts = $this->userProductRepository->getAllByUserId($userId);

        $totalPrice = $this->cartService->getTotalPrice($userProducts);

        require_once './../View/order.php';
    }

    public function postOrder(OrderRequest $request): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate($userId);

        if (empty($errors)) {
            $this->orderService->create($userId, $request->getName(), $request->getPhoneNumber(), $request->getAddress(), $request->getComment());
        }

        header("Location: /order");
    }
}