<?php

namespace Controller;

use Repository\ProductRepository;
use Service\Authentication\AuthenticationServiceInterface;
use Service\CartService;

class MainController

{
    private ProductRepository $productRepository;

    private AuthenticationServiceInterface $authenticationService;

    private CartService $cartService;

    public function __construct(AuthenticationServiceInterface $authenticationService, CartService $cartService, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->authenticationService = $authenticationService;
        $this->cartService = $cartService;
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