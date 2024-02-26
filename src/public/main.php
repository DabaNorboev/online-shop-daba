<?php
if (!isset($_COOKIE['user_id'])) {
    header('Location: /login.php');
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Каталог товаров</title>
</head>

<body>
<h2>Каталог потрясных товаров</h2>
<ul class="catalog">
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
    <li class="catalog-item">
        <h3>Потрясный товар</h3>
        <img src="https://cdn-icons-png.flaticon.com/512/3050/3050228.png" alt="Изображение товара">
        <p class="price">1.999 тугриков</p>
        <button class="accept">Возьми меня</button>
    </li>
</ul>
</body>

</html>
<style>
    body {
        font: 16px/1.5 sans-serif;
    }

    h2 {
        padding: 0 5px;

        text-align: center;
    }

    .catalog {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin: 0;
        padding: 0;

        list-style: none;
    }

    .catalog-item {
        display: block;
        width: 220px;

        margin: 5px;
        padding: 5px;

        text-align: center;

        border: 2px solid #87d4e0;
        border-radius: 20px;
    }

    img {
        max-width: 30%;
    }

    .price {
        margin: 0.5em;
    }

    .accept {
        display: block;
        width: 55%;

        margin: 0.4em auto;
        padding: 0.3em;

        color: #87d4e0;
        font-size: 100%;

        background: #fff;

        border: 2px solid #87d4e0;
        border-radius: 15px;

        cursor: pointer;

        transition: all 600ms ease;
    }

    .catalog-item:hover {
        box-shadow: 0 0 5px 2px rgba(11 144 188 / 50%);
    }

    .accept:hover {
        color: #fff;

        background: #87d4e0;

        transform: scale(1.2);
    }

    .accept:active {
        transform: scale(1.1);
        opacity: 0.7;
    }
</style>