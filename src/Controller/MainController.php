<?php
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
        $updatedProducts = $this->addQuantityToProducts($products,$userProducts);

        $cartCount = $this->getCartCount($userProducts);

        require_once './../View/main.php';
    }
    private function addQuantityToProducts(array $products, array $userProducts): array
    {
        $updatedProducts = [];
        if (empty($userProducts)) {
            foreach ($products as $product) {
                $product['quantity'] = 0;
                $updatedProducts[] = $product;
            }
        }
        else {
            foreach ($products as $product) {
                foreach ($userProducts as $userProduct) {
                    if ($product['id'] === $userProduct['product_id']) {
                        $product['quantity'] = $userProduct['quantity'];
                        break;
                    }
                    else {
                        $product['quantity'] = 0;
                    }
                }
                $updatedProducts[] = $product;
            }
        }


        return $updatedProducts;
    }
    private function getCartCount(array $products): int
    {
        $count = 0;
        foreach ($products as $product) {
            $count += $product['quantity'];
        }
        return $count;
    }
}