<?php
session_start();
include("../database/db.php");

// Customer must be logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Cart should not be empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

$total = 0;

// Calculate Grand Total
foreach ($_SESSION['cart'] as $product_id => $quantity) {

    $query = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $query);

    if ($product = mysqli_fetch_assoc($result)) {

        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

    }

}

// Insert into Orders table
$order_query = "INSERT INTO orders(customer_id,total_amount)
VALUES('$customer_id','$total')";

mysqli_query($conn, $order_query);

// Get Order ID
$order_id = mysqli_insert_id($conn);

// Insert Order Items
foreach ($_SESSION['cart'] as $product_id => $quantity) {

    $query = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $query);

    if ($product = mysqli_fetch_assoc($result)) {

        $price = $product['price'];

        mysqli_query($conn, "
        INSERT INTO order_items(order_id,product_id,quantity,price)
        VALUES('$order_id','$product_id','$quantity','$price')
        ");

    }

}

// Empty Cart
unset($_SESSION['cart']);

// Success Message
echo "<script>
alert('Order Placed Successfully!');
window.location='my-orders.php';
</script>";
?>