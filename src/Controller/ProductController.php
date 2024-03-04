<?php

class ProductController
{
    public function getAddProduct(): void
    {
        require_once './../View/add-product.php';
    }
    public function postAddProduct(): void
    {
        require_once './../View/add-product.php';
        [$errors, $values] = $this->validateAddProduct($_POST);

        if (empty($errors)) {
            $user_id = $_SESSION['user_id'];
            $product_id = $values['product_id'];
            $quantity = $values['quantity'];

            $productModel = new Product();
            $productModel->createPurchase($user_id, $product_id, $quantity);
            header('Location: /main');
        }
    }
    private function validateAddProduct(array $array): array
    {
        $values = [];
        $errors = [];

        foreach ($array as $key=>$value)
        {
            if (isset($value)){
                $values[$key] = $value;
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif ($key === 'product_id') {
                    if (ctype_digit($value)) {
                        $productModel = new Product();
                        $productById = $productModel->getOneById($value);
                        if (empty($productById)) {
                            $errors[$key] = 'Продукта с таким id не существует';
                        }
                    } else {
                        $errors[$key] = 'Id продукта может состоять только из цифр';
                    }
                }elseif ($key === 'quantity') {
                    if (!ctype_digit($value)) {
                        $errors[$key] = 'Введите число, используя цифры';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }

        return [$errors,$values];
    }
}
require_once './../Model/Product.php';