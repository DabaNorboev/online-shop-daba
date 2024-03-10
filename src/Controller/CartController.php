<?php
class CartController
{
    private Product $productModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }
    public function getCart(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])){
            header("Location: /login");
        }
        $userId = $_SESSION['user_id'];
        $productsOfCart = $this->getProductsOfCart($userId);
        $totalPrice = $this->getTotalPrice($productsOfCart);
        require_once './../View/cart.php';
    }
    public function getProductsOfCart($userId): array
    {
        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $productsOfCart = [];
        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productOfCart = [];
                foreach ($products as $product) {
                    if ($product['id'] === $userProduct['product_id']) {
                        $productOfCart['name'] = $product['name'];
                        $productOfCart['img_url'] = $product['img_url'];
                        $productOfCart['price'] = $product['price'];
                        $productOfCart['quantity'] = $userProduct['quantity'];
                        $productOfCart['sum'] = $productOfCart['quantity'] * $productOfCart['price'];
                    }
                }
                $productsOfCart[] = $productOfCart;
            }
        }

        return $productsOfCart;
    }
    public function getTotalPrice(array $products): int
    {
        $totalPrice = 0;
        if (!empty($products)){
            foreach($products as $product){
                $totalPrice += $product['sum'];
            }
        }
        return $totalPrice;
    }
//    public function getItemCountInCart(array $products): int
//    {
//        $count = 0;
//        foreach ($products as $product) {
//            $count += $product['quantity'];
//        }
//        return $count;
//    }
}