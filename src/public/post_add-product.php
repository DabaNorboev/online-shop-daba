<?php
function validate(array $array, PDO $pdo): array
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
                    $pdo = new PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
                    $stmt = $pdo->query("SELECT id FROM products");
                    $productIds = $stmt->fetchAll();
                    if (!in_array($value,$productIds)) {
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
require_once 'add-product.php';

$pdo = NEW PDO("pgsql:host=db; port=5432; dbname=laravel", 'root', 'root');
[$errors, $values] = validate($_POST, $pdo);

if (empty($errors)) {
    $user_id = $_SESSION['user_id'];
    $product_id = $values['product_id'];
    $quantity = $values['quantity'];

    $stmt = $pdo->prepare("INSERT INTO user_products (user_id,product_id,quantity) VALUES (:user_id, :product_id, :quantity)");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
}

