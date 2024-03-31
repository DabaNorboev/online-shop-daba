<?php

namespace Controller;

use Request\ChangeProductRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\CartService;

class CartController
{
    private CartService $cartService;

    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService, CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->authenticationService = $authenticationService;
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