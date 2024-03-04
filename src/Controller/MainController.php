<?php
require_once './../Model/Product.php';
require_once './../Model/User_product.php';
class MainController

{
    private Product $productModel;
    private User_product $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new User_product();
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
    public function postAddProduct(): void
    {
        session_start();
        $errors = $this->validateAddProduct($_POST);

        if (empty($errors)) {
            $user_id = $_SESSION['user_id'];
            $product_id = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $this->userProductModel->addProduct($user_id, $product_id, $quantity);
            $notification  = "Товар успешно добавлен в количестве $quantity шт";

        }
        $products = $this->productModel->getAll();
        require_once './../View/main.php';
    }
    private function validateAddProduct(array $array): array
    {
        $errors = [];

        foreach ($array as $key=>$value)
        {
            if (isset($value)){
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif ($key === 'quantity') {
                    if (!ctype_digit($value)) {
                        $errors[$key] = 'Введите число, используя цифры';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }

        return $errors;
    }
}