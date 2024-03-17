<?php

namespace Controller;

use Repository\ProductRepository;
use Repository\UserProductRepository;

class MainController

{
    private ProductRepository $productRepository;
    private UserProductRepository $userProductRepository;
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->userProductRepository = new UserProductRepository();
    }
    public function getMain(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $products = $this->addQuantityToProducts($userId);

        $cartCount = $this->getCartCount($products);

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
    private function getCartCount(array $products): int
    {
        $count = 0;
        foreach ($products as $product) {
            $count += $product->getQuantity();
        }
        return $count;
    }
}