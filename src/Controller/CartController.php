<?php

namespace Controller;

use Request\ChangeProductRequest;
use Service\AuthenticationService;
use Service\CartService;

class CartController
{
    private CartService $cartService;

    private AuthenticationService $authenticationService;

    public function __construct()
    {
        $this->cartService = new CartService();
        $this->authenticationService = new AuthenticationService();
    }

    public function addProduct(ChangeProductRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)) {
            $productId = $request->getProductId();

            $this->cartService->addProduct($productId);
        }

        header("Location: /main");
    }

    public function removeProduct(ChangeProductRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)) {
            $productId = $request->getProductId();

            $this->cartService->removeProduct($productId);
        }

        header("Location: /main");
    }

    public function getCart(): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $userProducts = $this->cartService->getProducts();
        $totalPrice = $this->cartService->getTotalPrice();

        require_once './../View/cart.php';
    }

    public function clearCart(): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $this->cartService->clear();

        header("Location: /main");
    }

    public function clearProduct(ChangeProductRequest $request): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)){

            $productId = $request->getProductId();

            $this->cartService->deleteProduct($productId);
        }

        header("Location: /cart");
    }
}