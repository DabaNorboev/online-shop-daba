<?php
class MainController

{
    private Product $productModel;
    public function __construct()
    {
        $this->productModel = new Product();
    }
    public function getMain(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $products = $this->productModel->getAll();
        require_once './../View/main.php';
    }
}