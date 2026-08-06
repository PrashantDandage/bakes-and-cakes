<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("../database/db.php");
include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container mt-5">

    <h2 class="fw-bold mb-4">
        🛒 Manage Orders
    </h2>

    <?php

    $query = "SELECT
                orders.*,
                customers.name,
                customers.email
              FROM orders
              INNER JOIN customers
              ON orders.customer_id = customers.id
              ORDER BY orders.order_date DESC";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle text-center">

                <thead class="table-dark">

                    <tr>

                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td>

                                #<?php echo $row['id']; ?>

                            </td>

                            <td>

                                <?php echo $row['name']; ?>

                            </td>

                            <td>

                                <?php echo $row['email']; ?>

                            </td>

                            <td>

                                <?php echo date("d M Y", strtotime($row['order_date'])); ?>

                            </td>

                            <td>

                                ₹<?php echo number_format($row['total_amount'], 2); ?>

                            </td>

                            <td>

                                <?php

                                if ($row['status'] == "Pending") {

                                    echo '<span class="badge bg-warning text-dark">Pending</span>';

                                } elseif ($row['status'] == "Confirmed") {

                                    echo '<span class="badge bg-primary">Confirmed</span>';

                                } elseif ($row['status'] == "Delivered") {

                                    echo '<span class="badge bg-success">Delivered</span>';

                                } else {

                                    echo '<span class="badge bg-danger">Cancelled</span>';

                                }

                                ?>

                            </td>

                            <td>

                                <a href="order-details.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm text-white">

                                    <i class="bi bi-eye"></i>

                                    View

                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    <?php } else { ?>

        <div class="alert alert-warning">

            No Orders Found.

        </div>

    <?php } ?>

</div>

</body>

</html>