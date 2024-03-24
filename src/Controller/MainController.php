<?php

namespace Controller;

use Repository\ProductRepository;
use Service\CartService;
use Service\AuthenticationService;

class MainController

{
    private ProductRepository $productRepository;

    private AuthenticationService $authenticationService;

    private CartService $cartService;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->authenticationService = new AuthenticationService();
        $this->cartService = new CartService();
    }
    public function getMain(): void
    {
        if (!$this->authenticationService->check()) {
            header("Location: /login");
        }

        $user = $this->authenticationService->getCurrentUser();
        $userId = $user->getId();

        $products = $this->productRepository->getAll();
        $userProducts = $this->cartService->getProducts();

        $cartCount = $this->cartService->getCount();

        require_once './../View/main.php';
    }
}