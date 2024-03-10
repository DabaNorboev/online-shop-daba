<?php
class ProductController
{
    private UserProduct $userProductModel;
    public function __construct()
    {
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
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $errors = $this->validateAddOrRemoveProduct($_POST);
        $userId = $_SESSION['user_id'];

        if (empty($errors)) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);
            if (empty($userProduct)) {
                $this->userProductModel->add($userId, $productId, $quantity);
            }
            else {
                $this->userProductModel->updateQuantityPlus($userId, $productId, $quantity);
            }
        }

        header("Location: /main");

    }
    private function validateAddOrRemoveProduct(array $productData): array
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
    public function removeProduct(): void
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: /login");
        }
        $errors = $this->validateAddOrRemoveProduct($_POST);
        $userId = $_SESSION['user_id'];

        if (empty($errors)) {
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];

            $userProduct = $this->userProductModel->getOneByUserIdProductId($userId,$productId);
            if (!empty($userProduct)) {
                if ($userProduct['quantity'] === 1) {
                    $this->userProductModel->remove($userId, $productId, $quantity);
                } elseif ($userProduct['quantity'] !== 0){
                    $this->userProductModel->updateQuantityMinus($userId, $productId, $quantity);
                }

            }
        }
        header("Location: /main");
    }
}
