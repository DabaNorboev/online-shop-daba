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

        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $updatedProducts = $this->addQuantityToProducts($products,$userProducts);

        $cartCount = $this->getCartCount($userProducts);

        require_once './../View/main.php';

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
    private function addQuantityToProducts(array $products, array $userProducts): array
    {
        $updatedProducts = [];
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
                if ($userProduct['quantity'] !== 0){
                    $this->userProductModel->updateQuantityMinus($userId, $productId, $quantity);
                }
            }
        }

        $products = $this->productModel->getAll();
        $userProducts = $this->userProductModel->getAllByUserId($userId);
        $updatedProducts = $this->addQuantityToProducts($products,$userProducts);

        $cartCount = $this->getCartCount($userProducts);

        require_once './../View/main.php';
    }
}
