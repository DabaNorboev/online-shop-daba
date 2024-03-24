<?php

namespace Controller;

use Request\OrderRequest;
use Service\AuthenticationService;
use Service\CartService;
use Service\OrderService;

class OrderController
{
    private OrderService $orderService;

    private CartService $cartService;

    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
        $this->authenticationService = new AuthenticationService();
    }

    public function getOrder(): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $userProducts = $this->cartService->getProducts();
        $totalPrice = $this->cartService->getTotalPrice();

        require_once './../View/order.php';
    }

    public function postOrder(OrderRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate($userId);

        if (empty($errors)) {
            $this->orderService->create($userId, $request->getName(), $request->getPhoneNumber(), $request->getAddress(), $request->getComment());
        }

        header("Location: /order");
    }
}