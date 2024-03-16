<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;

class MainController

{
    private Product $productModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }
    public function getMain(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];

        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $productsWithQuantity = $this->addQuantityToProducts($products,$userProducts);

        $cartCount = $this->getCartCount($userProducts);

        require_once './../View/main.php';
    }
    private function addQuantityToProducts(array $products, array $userProducts): array
    {
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

                    if ($product->getId() === $userProduct->getProductId()) {
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