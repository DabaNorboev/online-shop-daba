<?php

namespace Request;

use Repository\ProductRepository;

class ChangeProductRequest extends Request
{
    private ProductRepository $productRepository;

    public function __construct(string $method, string $uri, array $headers, array $body)
    {
        parent::__construct($method, $uri, $headers, $body);

        $this->productRepository = new ProductRepository();
    }

    public function getProductId(): string
    {
        return $this->body['product_id'];
    }
    public function validate(): array
    {
        $errors = [];

        $data = $this->body;

        foreach ($data as $key => $value)
        {
            if (isset($value)){
                $values[$key] = $value;
                if (empty($value)) {
                    $errors[$key] = "Это поле не должно быть пустым";
                }elseif ($key === 'product_id') {
                    if (ctype_digit($value)) {
                        $productById = $this->productRepository->getOneById($value);
                        if (empty($productById)) {
                            $errors[$key] = 'Продукта с таким id не существует';
                        }
                    } else {
                        $errors[$key] = 'Некорректный формат id продукта';
                    }
                }elseif ($key === 'quantity') {
                    if (!ctype_digit($value)) {
                        $errors[$key] = 'Некорректный формат количества продукта';
                    }
                }
            }else {
                $errors[$key] = "Это поле не должно быть пустым";
            }
        }

        return $errors;
    }
}