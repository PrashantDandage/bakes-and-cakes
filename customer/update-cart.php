<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $product_id = $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    if ($quantity > 0) {

        $_SESSION['cart'][$product_id] = $quantity;

    } else {

        unset($_SESSION['cart'][$product_id]);

    }

}

header("Location: cart.php");
exit();
?>