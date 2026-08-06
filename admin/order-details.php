<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("../database/db.php");
include("includes/header.php");
include("includes/navbar.php");

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = (int) $_GET['id'];

/* Order + Customer Details */

$query = "SELECT
            orders.*,
            customers.name,
            customers.email,
            customers.phone,
            customers.address
          FROM orders
          INNER JOIN customers
          ON orders.customer_id = customers.id
          WHERE orders.id='$order_id'";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: orders.php");
    exit();
}

$order = mysqli_fetch_assoc($result);
?>

<div class="container mt-5">

    <h2 class="fw-bold mb-4">
        📦 Order Details
    </h2>

    <!-- Customer Information -->

    <div class="card shadow mb-4">

        <div class="card-header bg-dark text-white">

            Customer Information

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <p><strong>Name :</strong> <?php echo $order['name']; ?></p>

                    <p><strong>Email :</strong> <?php echo $order['email']; ?></p>

                </div>

                <div class="col-md-6">

                    <p><strong>Phone :</strong> <?php echo $order['phone']; ?></p>

                    <p><strong>Address :</strong> <?php echo $order['address']; ?></p>

                </div>

            </div>

        </div>

    </div>

    <!-- Ordered Products -->

    <div class="card shadow mb-4">

        <div class="card-header bg-success text-white">

            Ordered Products

        </div>

        <div class="card-body">

            <table class="table table-bordered text-center align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    $item_query = "SELECT
                order_items.*,
                products.name,
                products.image
               FROM order_items
               INNER JOIN products
               ON order_items.product_id = products.id
               WHERE order_items.order_id='$order_id'";

                    $item_result = mysqli_query($conn, $item_query);

                    while ($item = mysqli_fetch_assoc($item_result)) {

                        $item_total = $item['price'] * $item['quantity'];

                        ?>

                        <tr>

                            <td>

                                <img src="../uploads/<?php echo $item['image']; ?>" width="80" class="rounded">

                            </td>

                            <td>

                                <?php echo $item['name']; ?>

                            </td>

                            <td>

                                ₹<?php echo number_format($item['price'], 2); ?>

                            </td>

                            <td>

                                <?php echo $item['quantity']; ?>

                            </td>

                            <td>

                                ₹<?php echo number_format($item_total, 2); ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

    <!-- Order Status -->

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            Update Order Status

        </div>

        <div class="card-body">

            <form action="update-order-status.php" method="POST">

                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                <div class="row">

                    <div class="col-md-8">

                        <select name="status" class="form-select">

                            <option <?php if ($order['status'] == "Pending")
                                echo "selected"; ?>>
                                Pending
                            </option>

                            <option <?php if ($order['status'] == "Confirmed")
                                echo "selected"; ?>>
                                Confirmed
                            </option>

                            <option <?php if ($order['status'] == "Delivered")
                                echo "selected"; ?>>
                                Delivered
                            </option>

                            <option <?php if ($order['status'] == "Cancelled")
                                echo "selected"; ?>>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <button class="btn btn-success w-100">

                            Update Status

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <a href="orders.php" class="btn btn-secondary mt-4">

        ← Back to Orders

    </a>

</div>

</body>

</html>