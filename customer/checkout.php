<?php
session_start();
include("../database/db.php");

// Customer must be logged in
if (!isset($_SESSION['customer_id'])) {

    header("Location: login.php");
    exit();

}

// Cart must not be empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    header("Location: cart.php");
    exit();

}

$customer_id = $_SESSION['customer_id'];

// Fetch customer details
$query = "SELECT * FROM customers WHERE id='$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

$total = 0;

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container py-5">

    <h2 class="fw-bold mb-4">

        Checkout

    </h2>

    <div class="row">

        <!-- Customer Details -->

        <div class="col-lg-6">

            <div class="card shadow-sm">

                <div class="card-header bg-dark text-white">

                    Customer Details

                </div>

                <div class="card-body">

                    <div class="mb-3">

                        <label>Name</label>

                        <input type="text" class="form-control" value="<?php echo $customer['name']; ?>" readonly>

                    </div>

                    <div class="mb-3">

                        <label>Email</label>

                        <input type="email" class="form-control" value="<?php echo $customer['email']; ?>" readonly>

                    </div>

                    <div class="mb-3">

                        <label>Phone</label>

                        <input type="text" class="form-control" value="<?php echo $customer['phone']; ?>" readonly>

                    </div>

                    <div class="mb-3">

                        <label>Delivery Address</label>

                        <textarea class="form-control" rows="4" readonly><?php echo $customer['address']; ?></textarea>

                    </div>

                </div>

            </div>

        </div>

        <!-- Order Summary -->

        <div class="col-lg-6">

            <div class="card shadow-sm">

                <div class="card-header bg-success text-white">

                    Order Summary

                </div>

                <div class="card-body">

                    <table class="table">

                        <thead>

                            <tr>

                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            foreach ($_SESSION['cart'] as $product_id => $quantity) {

                                $query = "SELECT * FROM products WHERE id='$product_id'";
                                $result = mysqli_query($conn, $query);
                                $product = mysqli_fetch_assoc($result);

                                $subtotal = $product['price'] * $quantity;

                                $total += $subtotal;

                                ?>

                                <tr>

                                    <td>

                                        <?php echo $product['name']; ?>

                                    </td>

                                    <td>

                                        <?php echo $quantity; ?>

                                    </td>

                                    <td>

                                        ₹<?php echo number_format($subtotal, 2); ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        </tbody>

                    </table>

                    <hr>

                    <h4 class="text-end">

                        Grand Total :

                        <span class="text-danger">

                            ₹<?php echo number_format($total, 2); ?>

                        </span>

                    </h4>

                    <form action="place-order.php" method="POST">

                        <button class="btn btn-success w-100 mt-3">

                            Place Order

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>