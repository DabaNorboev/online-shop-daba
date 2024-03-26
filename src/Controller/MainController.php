<?php

namespace Controller;

use Repository\ProductRepository;
use Service\Authentication\AuthenticationServiceSession;
use Service\CartService;

class MainController

{
    private ProductRepository $productRepository;

    private AuthenticationServiceSession $authenticationService;

    private CartService $cartService;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->authenticationService = new AuthenticationServiceSession();
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