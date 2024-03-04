<?php
require_once './../Model/Product.php';
require_once './../Model/User_product.php';
class ProductController
{
    private Product $productModel;
    private User_product $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new User_product();
    }

    public function getAddProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        require_once './../View/add-product.php';
    }

}
