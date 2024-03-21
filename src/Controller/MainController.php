<?php

namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;
use Service\CartService;
use Service\AuthenticationService;

class MainController

{
    private ProductRepository $productRepository;

    private UserProductRepository $userProductRepository;

    private AuthenticationService $authenticationService;

    private CartService $cartService;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->userProductRepository = new UserProductRepository();
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

        $products = $this->addQuantityToProducts($userId);

        $cartCount = $this->cartService->getCartCount($products);

        require_once './../View/main.php';
    }

    private function addQuantityToProducts(string $userId): array
    {
        $userProducts = $this->userProductRepository->getAllByUserId($userId);
        $products = $this->productRepository->getAll();

        $productsWithQuantity = [];

        if (empty($userProducts)) {
            foreach ($products as $product) {
                $product->setQuantity(0);
                $productsWithQuantity[] = $product;
            }
        }
        else {
            foreach ($products as $product) {
                foreach ($userProducts as $userProduct) {

                    if ($product->getId() === $userProduct->getProduct()->getId()) {
                        $product->setQuantity($userProduct->getQuantity());
                        break;
                    }
                    else {
                        $product->setQuantity(0);
                    }
                }
                $productsWithQuantity[] = $product;
            }
        }

        return $productsWithQuantity;
    }
}