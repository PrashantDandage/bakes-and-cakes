<?php
session_start();
include("../database/db.php");

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container py-5">

    <h2 class="fw-bold mb-4">
        📦 My Orders
    </h2>

    <?php

    $query = "SELECT * FROM orders
              WHERE customer_id='$customer_id'
              ORDER BY order_date DESC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover text-center align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Order ID</th>

                        <th>Date</th>

                        <th>Total Amount</th>

                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($order = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td>#<?php echo $order['id']; ?></td>

                            <td>

                                <?php echo date("d M Y", strtotime($order['order_date'])); ?>

                            </td>

                            <td>

                                ₹<?php echo number_format($order['total_amount'], 2); ?>

                            </td>

                            <td>

                                <?php

                                if ($order['status'] == "Pending") {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                } elseif ($order['status'] == "Confirmed") {
                                    echo '<span class="badge bg-primary">Confirmed</span>';
                                } elseif ($order['status'] == "Delivered") {
                                    echo '<span class="badge bg-success">Delivered</span>';
                                } else {
                                    echo '<span class="badge bg-danger">Cancelled</span>';
                                }

                                ?>

                            </td>

                            <td>

                                <a href="order-details.php?id=<?php echo $order['id']; ?>"
                                    class="btn btn-info btn-sm text-white">

                                    View Details

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

        <?php

    } else {

        ?>

        <div class="alert alert-warning">

            You haven't placed any orders yet.

        </div>

        <a href="shop.php" class="btn btn-success">

            Continue Shopping

        </a>

        <?php

    }

    ?>

</div>

<?php include("../includes/footer.php"); ?>