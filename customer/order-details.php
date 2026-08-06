<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my-orders.php");
    exit();
}

$order_id = (int) $_GET['id'];
$customer_id = $_SESSION['customer_id'];

// Get Order
$order_query = "SELECT * FROM orders
                WHERE id='$order_id'
                AND customer_id='$customer_id'";

$order_result = mysqli_query($conn, $order_query);

if (mysqli_num_rows($order_result) == 0) {
    header("Location: my-orders.php");
    exit();
}

$order = mysqli_fetch_assoc($order_result);

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container py-5">

    <div class="card shadow">

        <div class="card-header bg-dark text-white">

            <h3>

                Order #<?php echo $order['id']; ?>

            </h3>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-4">

                    <strong>Date :</strong><br>

                    <?php echo date("d M Y", strtotime($order['order_date'])); ?>

                </div>

                <div class="col-md-4">

                    <strong>Status :</strong><br>

                    <?php echo $order['status']; ?>

                </div>

                <div class="col-md-4">

                    <strong>Total :</strong><br>

                    ₹<?php echo number_format($order['total_amount'], 2); ?>

                </div>

            </div>

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

                    $query = "SELECT
            order_items.*,
            products.name,
            products.image
          FROM order_items
          INNER JOIN products
          ON order_items.product_id = products.id
          WHERE order_items.order_id='$order_id'";

                    $result = mysqli_query($conn, $query);

                    while ($item = mysqli_fetch_assoc($result)) {

                        $total = $item['price'] * $item['quantity'];

                        ?>

                        <tr>

                            <td>

                                <img src="../uploads/<?php echo $item['image']; ?>" width="80">

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

                                ₹<?php echo number_format($total, 2); ?>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

            <a href="my-orders.php" class="btn btn-secondary">

                ← Back To My Orders

            </a>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>