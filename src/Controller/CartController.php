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
        $userId = $_SESSION['user_id'];
        if (!isset($userId)) {
            header("Location: /login");
        }
        $cartAndTotalPrice = $this->createCart($userId);
        $cart = $cartAndTotalPrice['cart'];
        $totalPrice = $cartAndTotalPrice['total_price'];
        if (empty($cart)) {
            $notification = "Корзина пуста";
        }
        require_once './../View/cart.php';
    }
    public function createCart($userId): array
    {
        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $cart = [];
        $totalPrice = 0;
        foreach ($userProducts as $userProduct) {
            $productOfCart = [];
            foreach ($products as $product) {
                if ($product['id'] === $userProduct['product_id']) {
                    $productOfCart['name'] = $product['name'];
                    $productOfCart['img_url'] = $product['img_url'];
                    $productOfCart['price'] = $product['price'];
                    $productOfCart['quantity'] = $userProduct['quantity'];
                    $productOfCart['sum'] = $productOfCart['quantity'] * $productOfCart['price'];
                    $totalPrice += $productOfCart['sum'];
                }
            }
            $cart[] = $productOfCart;
        }
        return ['cart' => $cart, 'total_price' => $totalPrice];
    }
}