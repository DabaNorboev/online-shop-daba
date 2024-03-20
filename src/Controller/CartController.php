<?php

namespace Controller;

use Repository\UserProductRepository;
use Request\ChangeProductRequest;
use Service\UserService;
use Service\CartService;

class CartController
{
    private UserProductRepository $userProductRepository;

    private CartService $cartService;

    private UserService $userService;

    public function __construct()
    {
        $this->userProductRepository = new UserProductRepository();
        $this->cartService = new CartService();
        $this->userService = new UserService();
    }

    public function addProduct(ChangeProductRequest $request): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)) {
            $productId = $request->getProductId();

            $this->cartService->addProduct($productId,$userId);
        }

        header("Location: /main");
    }

    public function removeProduct(ChangeProductRequest $request): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)) {
            $productId = $request->getProductId();

            $this->cartService->removeProduct($productId,$userId);
        }

        header("Location: /main");
    }

    public function getCart(): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $userProducts = $this->userProductRepository->getAllByUserId($userId);

        $totalPrice = $this->cartService->getTotalPrice($userProducts);

        require_once './../View/cart.php';
    }

    public function clearCart(): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $this->userProductRepository->clearByUserId($userId);

        header("Location: /main");
    }

    public function clearProduct(ChangeProductRequest $request): void
    {
        if (!$this->userService->check()) {
            header("Location: /login");
        }

        $user = $this->userService->getCurrentUser();
        $userId = $user->getId();

        $errors = $request->validate();

        if (empty($errors)){

            $productId = $request->getProductId();

            $this->userProductRepository->clearProductByUserIdProductId($userId,$productId);
        }

        header("Location: /cart");
    }
}