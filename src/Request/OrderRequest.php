<?php

namespace Request;

use Repository\UserProductRepository;

class OrderRequest extends Request
{
    private UserProductRepository $userProductRepository;
    public function __construct(string $method, string $uri, array $headers, array $body)
    {
        parent::__construct($method, $uri, $headers, $body);
        $this->userProductRepository = new UserProductRepository();
    }

    public function getName()
    {
        return $this->body['name'];
    }

    public function getPhoneNumber()
    {
        return $this->body['tel'];
    }

    public function getAddress()
    {
        return $this->body['address'];
    }

    public function getComment()
    {
        return $this->body['comment'];
    }

    public function validate(string $userId): array
    {
        $errors = [];

        $orderData = $this->body;

        foreach ($orderData as $key=> $value)
        {
            if (isset($value)){
                if (empty($value)) {
                    if ($key !== 'comment'){
                        $errors['comment'] = "Это поле не должно быть пустым";
                    }
                }elseif ($key === 'name'){
                    if (mb_strlen($value, 'UTF-8') < 2) {
                        $errors['name'] = 'Минимально допустимая длина имени - 2 символа';
                    }
                }elseif ($key === 'tel'){
                    if (ctype_digit($value)) {
                        if (mb_strlen($value, 'UTF-8') !== 11) {
                            $errors['tel'] = 'Количество цифр в номере телефона не соответствует образцу';
                        }
                    } else {
                        $errors['tel'] = 'Номер телефона может состоять только из цифр, посмотрите образец';
                    }
                }elseif ($key === 'address'){
                    if (mb_strlen($value, 'UTF-8') < 5) {
                        $errors['address'] = 'Минимально допустимая длина адреса - 5 символов';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }

        $productsOfCart = $this->userProductRepository->getAllByUserId($userId);

        if (empty($productsOfCart)){
            $errors['products-of-cart'] = 'Нельзя оформить заказ, т.к ваша корзина пуста';
        }

        return $errors;
    }
}