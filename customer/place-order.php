<?php

session_start();

include("../database/db.php");


// ======================================================
// CUSTOMER LOGIN CHECK
// ======================================================

if (!isset($_SESSION['customer_id'])) {

    header("Location: login.php");
    exit();

}

$customer_id = (int) $_SESSION['customer_id'];


// ======================================================
// CART CHECK
// ======================================================

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    header("Location: cart.php");
    exit();

}


// ======================================================
// START DATABASE TRANSACTION
// ======================================================

mysqli_begin_transaction($conn);

try {

    $total = 0;
    $cart_products = [];


    // ==================================================
    // GET PRODUCTS AND CALCULATE TOTAL
    // ==================================================

    foreach ($_SESSION['cart'] as $product_id => $quantity) {

        $product_id = (int) $product_id;
        $quantity = (int) $quantity;


        // Validate quantity

        if ($quantity <= 0) {
            throw new Exception("Invalid product quantity.");
        }


        // Get current product information

        $product_stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, price, status
             FROM products
             WHERE id = ?
             LIMIT 1"
        );


        if (!$product_stmt) {
            throw new Exception("Unable to prepare product query.");
        }


        mysqli_stmt_bind_param(
            $product_stmt,
            "i",
            $product_id
        );

        mysqli_stmt_execute($product_stmt);

        $product_result = mysqli_stmt_get_result($product_stmt);

        $product = mysqli_fetch_assoc($product_result);

        mysqli_stmt_close($product_stmt);


        // Product does not exist

        if (!$product) {

            throw new Exception(
                "One of the products in your cart is no longer available."
            );

        }


        // Check product availability

        if ($product['status'] !== 'Available') {

            throw new Exception(
                $product['name'] . " is currently out of stock."
            );

        }


        // Calculate subtotal using database price

        $price = (float) $product['price'];

        $subtotal = $price * $quantity;

        $total += $subtotal;


        // Store validated product information

        $cart_products[] = [
            'id' => $product_id,
            'price' => $price,
            'quantity' => $quantity
        ];

    }


    // ==================================================
    // VALIDATE TOTAL
    // ==================================================

    if ($total <= 0) {

        throw new Exception("Invalid order total.");

    }


    // ==================================================
    // INSERT ORDER
    // ==================================================

    $order_stmt = mysqli_prepare(
        $conn,
        "INSERT INTO orders
        (customer_id, total_amount)
        VALUES (?, ?)"
    );


    if (!$order_stmt) {

        throw new Exception("Unable to prepare order query.");

    }


    mysqli_stmt_bind_param(
        $order_stmt,
        "id",
        $customer_id,
        $total
    );


    if (!mysqli_stmt_execute($order_stmt)) {

        mysqli_stmt_close($order_stmt);

        throw new Exception("Unable to create order.");

    }


    // Get newly created order ID

    $order_id = mysqli_insert_id($conn);

    mysqli_stmt_close($order_stmt);


    if ($order_id <= 0) {

        throw new Exception("Unable to create order ID.");

    }


    // ==================================================
    // INSERT ORDER ITEMS
    // ==================================================

    $item_stmt = mysqli_prepare(
        $conn,
        "INSERT INTO order_items
        (order_id, product_id, quantity, price)
        VALUES (?, ?, ?, ?)"
    );


    if (!$item_stmt) {

        throw new Exception("Unable to prepare order item query.");

    }


    foreach ($cart_products as $item) {

        $product_id = $item['id'];
        $quantity = $item['quantity'];
        $price = $item['price'];


        mysqli_stmt_bind_param(
            $item_stmt,
            "iiid",
            $order_id,
            $product_id,
            $quantity,
            $price
        );


        if (!mysqli_stmt_execute($item_stmt)) {

            mysqli_stmt_close($item_stmt);

            throw new Exception(
                "Unable to add products to the order."
            );

        }

    }


    mysqli_stmt_close($item_stmt);


    // ==================================================
    // COMMIT TRANSACTION
    // ==================================================

    mysqli_commit($conn);


    // ==================================================
    // CLEAR CART
    // ==================================================

    unset($_SESSION['cart']);


    // ==================================================
    // SUCCESS
    // ==================================================

    echo "<script>
            alert('Order Placed Successfully!');
            window.location='my-orders.php';
          </script>";

    exit();


} catch (Exception $e) {


    // ==================================================
    // ROLLBACK IF SOMETHING FAILS
    // ==================================================

    mysqli_rollback($conn);


    echo "<script>
            alert(" . json_encode(
        "Order could not be placed. " . $e->getMessage()
    ) . ");
            window.location='cart.php';
          </script>";

    exit();

}

?>