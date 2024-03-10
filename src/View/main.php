<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Каталог товаров</title>
</head>

<body>
<h2>Каталог товаров</h2>

    <form action = "/logout" method = "post">
        <button type = "submit">LOGOUT</button>
    </form>
<a href="/cart" class="button-cart">Корзина (<?php echo $cartCount; ?>)</a>

<ul class="catalog">

    <?php foreach ($updatedProducts as $product): ?>

        <li class="catalog-item">
            <h3><?php echo $product['name'] ?></h3>
            <img src="<?php echo $product['img_url'] ?>" alt="Изображение товара">
            <p class="price"><?php echo $product['price'] ?> руб</p>
            <p><?php echo $product['description'] ?></p>

            <p class="price"><?php echo $product['quantity'] ?></p>
            <form action = "/add-product" method = "post">
                <input type="hidden" name="product_id" id="product_id" required value = "<?php echo $product['id'] ?>">
                <input type="hidden" name="quantity" id="quantity" required value = 1>
                <button type="submit" class="registerbtn">+</button>
            </form>

            <form action = "/rm-product" method="post">
                <input type="hidden" name="product_id" id="product_id" required value = "<?php echo $product['id'] ?>">
                <input type="hidden" name="quantity" id="quantity" required value = 1>
                <button type="submit" class="registerbtn">-</button>
            </form>

        </li>
    <?php endforeach; ?>

</ul>

<p style="color: black"><?php echo $errors['quantity'] ?? '';?></p>
<p style="color: black"><?php if (isset($quantity)) { echo "Товар успешно добавлен в количестве $quantity шт";}?></p>
</body>

</html>
<style>
    @import url(https://fonts.googleapis.com/css?family=Oswald:400);

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

    a:hover {
        width: 100px;
    }

    a:hover .logout{
        opacity: .9;
    }

    a {
        text-decoration: none;
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