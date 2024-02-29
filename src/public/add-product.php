<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Покупка товаров</title>
</head>
<form action="post_add-product.php" method="post">
    <div class="container">
<!--        <h1>Регистрация</h1>-->
        <p>Пожалуйста, заполните эту форму, чтобы совершить покупку.</p>
        <hr>

        <label for="email"><b>Id продукта</b></label>
        <label style="color: red"><?php echo $errors['product_id'] ?? '';?></label>
        <input type="text" placeholder="Введите id товара" name="product_id" id="product_id" required>

        <label for="psw"><b>Количество</b></label>
        <label style = "color: red"><?php echo $errors['quantity'] ?? '';?></label>
        <input type="text" placeholder="Введите количество" name="quantity" id="quantity" required>

        <hr>

        <button type="submit" class="registerbtn">Купить</button>
    </div>

</form>
<style>
    * {box-sizing: border-box}

    /* Add padding to containers */
    .container {
        padding: 16px;
    }

    /* Full-width input fields */
    input[type=text], input[type=password] {
        width: 100%;
        padding: 15px;
        margin: 5px 0 22px 0;
        display: inline-block;
        border: none;
        background: #f1f1f1;
    }

    input[type=text]:focus, input[type=password]:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Overwrite default styles of hr */
    hr {
        border: 1px solid #f1f1f1;
        margin-bottom: 25px;
    }

    /* Set a style for the submit/register button */
    .registerbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        width: 100%;
        opacity: 0.9;
    }

    .registerbtn:hover {
        opacity:1;
    }

    /* Add a blue text color to links */
    a {
        color: dodgerblue;
    }

    /* Set a grey background color and center the text of the "sign in" section */
    .signin {
        background-color: #f1f1f1;
        text-align: center;
    }
</style>