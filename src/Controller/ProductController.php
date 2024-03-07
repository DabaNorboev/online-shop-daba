<?php
class ProductController
{
    private Product $productModel;
    private UserProduct $userProductModel;
    public function __construct()
    {
        $this->productModel = new Product();
        $this->userProductModel = new UserProduct();
    }

    public function getAddProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        require_once './../View/add-product.php';
    }
    public function postAddProduct(): void
    {
        session_start();
        $errors = $this->validateAddProduct($_POST);

        if (empty($errors)) {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);
            if (empty($userProduct)) {
                $this->userProductModel->add($userId, $productId, $quantity);
            }
            else {
                $this->userProductModel->update($userId, $productId, $quantity);
            }
            $notification  = "Товар успешно добавлен в количестве $quantity шт";

        }
        $products = $this->productModel->getAll();
        require_once './../View/main.php';
    }
    private function validateAddProduct(array $productData): array
    {
        $errors = [];

        foreach ($productData as $key=> $value)
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
