<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Корзина</title>
</head>

<body>
<h2>Корзина</h2>
<form action = "/logout" method = "post">
    <button type = "submit">LOGOUT</button>
</form>
<a href="/main" class="button-cart">Каталог</a>
<ul class="catalog">

    <?php foreach ($productsOfCart as $product): ?>
            <li class="catalog-item">
                <h3><?php echo $product['name'] ?></h3>
                <img src="<?php echo $product['img_url'] ?>" alt="Изображение товара">
<!--                <p class="price">Цена за шт: --><?php //echo $product['price'] ?><!-- руб</p>-->
                <p class="price">Количество: <?php echo $product['quantity'] ?> шт</p>
                <p class="price">Цена: <?php echo $product['sum'] ?> руб</p>
            </li>

    <?php endforeach; ?>

</ul>

<p class="price">Итоговая сумма заказа: <?php echo $totalPrice;?> руб</p>
<p style="color: black"><?php echo $errors['quantity'] ?? ''?></p>
<p style="color: black"><?php if (empty($productsOfCart)) { echo 'Корзина пуста';}?></p>
<button onclick="window.location.href='/order'">Перейти к оформлению заказа</button>





</body>

</html>
<style>
    .button-cart {
        background-color: #04AA6D; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
    }
    .navigation {
        width: 100%;
        background-color: black;
    }
    .img-logout {
        width: 25px;
        border-radius: 50px;
        float: left;
    }

    .logout {
        font-size: .8em;
        font-family: 'Oswald', sans-serif;
        position: relative;
        right: -18px;
        bottom: -4px;
        overflow: hidden;
        letter-spacing: 3px;
        opacity: 0;
        transition: opacity .45s;
        -webkit-transition: opacity .35s;

    }

    .button {
        text-decoration: none;
        float: right;
        padding: 12px;
        margin: 15px;
        color: white;
        width: 25px;
        background-color: black;
        transition: width .35s;
        -webkit-transition: width .35s;
        overflow: hidden
    }
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