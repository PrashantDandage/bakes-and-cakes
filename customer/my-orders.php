<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/db.php");


// =====================================================
// CUSTOMER LOGIN CHECK
// =====================================================

if (!isset($_SESSION['customer_id'])) {

    header("Location: login.php");
    exit();

}

$customer_id = (int) $_SESSION['customer_id'];


// =====================================================
// CUSTOMER INFORMATION
// =====================================================

$customer_name = $_SESSION['customer_name'] ?? 'Customer';


// =====================================================
// FETCH CUSTOMER ORDERS
// =====================================================

$query = "
    SELECT
        id,
        total_amount,
        order_date,
        status
    FROM orders
    WHERE customer_id = '$customer_id'
    ORDER BY order_date DESC
";

$result = mysqli_query($conn, $query);


// =====================================================
// HEADER + NAVBAR
// =====================================================

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">


    <!-- =================================================
         PAGE HEADING
    ================================================== -->

    <div class="orders-heading mb-5">

        <div>

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb mb-3">

                    <li class="breadcrumb-item">

                        <a href="dashboard.php">
                            Dashboard
                        </a>

                    </li>

                    <li class="breadcrumb-item active" aria-current="page">

                        My Orders

                    </li>

                </ol>

            </nav>


            <h1 class="fw-bold">

                <i class="bi bi-receipt-cutoff text-warning"></i>

                My Orders

            </h1>

            <p class="text-muted mb-0">

                View and track all your bakery orders.

            </p>

        </div>


        <a href="shop.php" class="btn btn-success orders-shop-btn">

            <i class="bi bi-shop"></i>

            Continue Shopping

        </a>

    </div>


    <!-- =================================================
         ORDERS TABLE
    ================================================== -->

    <div class="card orders-card">

        <div class="card-header">

            <div>

                <h4 class="mb-1 fw-bold">

                    Order History

                </h4>

                <p class="text-muted mb-0">

                    Hello
                    <?php echo htmlspecialchars($customer_name); ?>,
                    here are your orders.

                </p>

            </div>

        </div>


        <div class="card-body p-0">

            <?php

            if ($result && mysqli_num_rows($result) > 0) {

                ?>

                <div class="table-responsive">

                    <table class="table orders-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>
                                    Order ID
                                </th>

                                <th>
                                    Order Date
                                </th>

                                <th>
                                    Total Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="text-center">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            while ($order = mysqli_fetch_assoc($result)) {


                                // =========================================
                                // STATUS BADGE
                                // =========================================
                        
                                $status = $order['status'];

                                switch ($status) {

                                    case 'Pending':

                                        $status_class = 'status-pending';

                                        $status_icon = 'bi-clock-fill';

                                        break;


                                    case 'Confirmed':

                                        $status_class = 'status-confirmed';

                                        $status_icon = 'bi-check-circle-fill';

                                        break;


                                    case 'Delivered':

                                        $status_class = 'status-delivered';

                                        $status_icon = 'bi-box-seam-fill';

                                        break;


                                    case 'Cancelled':

                                        $status_class = 'status-cancelled';

                                        $status_icon = 'bi-x-circle-fill';

                                        break;


                                    default:

                                        $status_class = 'status-default';

                                        $status_icon = 'bi-info-circle-fill';

                                        break;

                                }

                                ?>

                                <tr>


                                    <!-- ORDER ID -->

                                    <td>

                                        <span class="order-id">

                                            #<?php echo $order['id']; ?>

                                        </span>

                                    </td>


                                    <!-- ORDER DATE -->

                                    <td>

                                        <div class="order-date">

                                            <i class="bi bi-calendar3"></i>

                                            <?php

                                            echo date(
                                                "d M Y",
                                                strtotime($order['order_date'])
                                            );

                                            ?>

                                        </div>

                                        <small class="text-muted">

                                            <?php

                                            echo date(
                                                "h:i A",
                                                strtotime($order['order_date'])
                                            );

                                            ?>

                                        </small>

                                    </td>


                                    <!-- TOTAL -->

                                    <td>

                                        <span class="order-amount">

                                            ₹<?php

                                            echo number_format(
                                                $order['total_amount'],
                                                2
                                            );

                                            ?>

                                        </span>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <span class="order-status <?php echo $status_class; ?>">

                                            <i class="bi <?php echo $status_icon; ?>"></i>

                                            <?php echo htmlspecialchars($status); ?>

                                        </span>

                                    </td>


                                    <!-- ACTION -->

                                    <td class="text-center">

                                        <a href="order-details.php?id=<?php echo $order['id']; ?>"
                                            class="btn btn-dark btn-sm order-view-btn">

                                            <i class="bi bi-eye"></i>

                                            View Details

                                        </a>

                                    </td>

                                </tr>

                                <?php

                            }

                            ?>

                        </tbody>

                    </table>

                </div>

                <?php

            } else {

                ?>

                <!-- =============================================
                     NO ORDERS
                ============================================== -->

                <div class="empty-orders text-center">

                    <div class="empty-orders-icon">

                        <i class="bi bi-receipt"></i>

                    </div>

                    <h3>

                        No Orders Yet

                    </h3>

                    <p class="text-muted">

                        You haven't placed any orders yet.

                        Start shopping and your orders will appear here.

                    </p>

                    <a href="shop.php" class="btn btn-success">

                        <i class="bi bi-shop"></i>

                        Start Shopping

                    </a>

                </div>

                <?php

            }

            ?>

        </div>

    </div>


    <!-- =================================================
         BOTTOM ACTIONS
    ================================================== -->

    <div class="orders-bottom-actions mt-4">

        <a href="dashboard.php" class="btn btn-outline-dark">

            <i class="bi bi-arrow-left"></i>

            Back to Dashboard

        </a>


        <a href="shop.php" class="btn btn-success">

            <i class="bi bi-cart-plus"></i>

            Continue Shopping

        </a>

    </div>

</div>


<!-- =====================================================
     MY ORDERS CSS
====================================================== -->

<style>
    /* =========================================================
   PAGE HEADING
========================================================= */

    .orders-heading {
        display: flex;

        justify-content: space-between;

        align-items: end;

        gap: 20px;
    }

    .orders-heading h1 {
        color: #2c1d12;
    }

    .orders-shop-btn {
        border-radius: 30px;

        padding: 11px 22px;

        font-weight: 600;

        white-space: nowrap;
    }


    /* =========================================================
   ORDERS CARD
========================================================= */

    .orders-card {
        border: none;

        border-radius: 20px;

        overflow: hidden;

        box-shadow:
            0 8px 30px rgba(0, 0, 0, 0.08);
    }

    .orders-card .card-header {
        background: #ffffff;

        padding: 22px 25px;

        border-bottom: 1px solid #eeeeee;
    }


    /* =========================================================
   TABLE
========================================================= */

    .orders-table thead th {
        background: #212529;

        color: #ffffff;

        padding: 16px 20px;

        font-size: 14px;

        white-space: nowrap;
    }

    .orders-table tbody td {
        padding: 18px 20px;

        border-color: #eeeeee;
    }

    .orders-table tbody tr {
        transition: all 0.2s ease;
    }

    .orders-table tbody tr:hover {
        background: #fffaf5;
    }


    /* =========================================================
   ORDER ID
========================================================= */

    .order-id {
        display: inline-block;

        padding: 7px 12px;

        border-radius: 10px;

        background: #fff1e6;

        color: #d35400;

        font-weight: 700;
    }


    /* =========================================================
   DATE
========================================================= */

    .order-date {
        display: flex;

        align-items: center;

        gap: 7px;

        font-weight: 600;
    }

    .order-date i {
        color: #d35400;
    }


    /* =========================================================
   AMOUNT
========================================================= */

    .order-amount {
        color: #dc3545;

        font-size: 18px;

        font-weight: 700;
    }


    /* =========================================================
   STATUS
========================================================= */

    .order-status {
        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 7px 13px;

        border-radius: 30px;

        font-size: 13px;

        font-weight: 600;

        white-space: nowrap;
    }

    .status-pending {
        background: #fff3cd;

        color: #856404;
    }

    .status-confirmed {
        background: #cfe2ff;

        color: #084298;
    }

    .status-delivered {
        background: #d1e7dd;

        color: #0f5132;
    }

    .status-cancelled {
        background: #f8d7da;

        color: #842029;
    }

    .status-default {
        background: #e9ecef;

        color: #495057;
    }


    /* =========================================================
   VIEW BUTTON
========================================================= */

    .order-view-btn {
        border-radius: 30px;

        padding: 8px 16px;

        font-weight: 600;
    }


    /* =========================================================
   EMPTY ORDERS
========================================================= */

    .empty-orders {
        padding: 80px 25px;
    }

    .empty-orders-icon {
        width: 90px;
        height: 90px;

        margin: 0 auto 20px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #fff1e6;

        color: #d35400;

        font-size: 42px;
    }

    .empty-orders h3 {
        font-weight: 700;

        color: #2c1d12;
    }

    .empty-orders p {
        max-width: 500px;

        margin: 0 auto 25px;

        line-height: 1.7;
    }


    /* =========================================================
   BOTTOM ACTIONS
========================================================= */

    .orders-bottom-actions {
        display: flex;

        justify-content: space-between;

        align-items: center;
    }

    .orders-bottom-actions .btn {
        border-radius: 30px;

        padding: 10px 20px;

        font-weight: 600;
    }


    /* =========================================================
   MOBILE
========================================================= */

    @media (max-width: 768px) {

        .orders-heading {
            flex-direction: column;

            align-items: flex-start;
        }

        .orders-shop-btn {
            width: 100%;
        }

        .orders-bottom-actions {
            flex-direction: column;

            gap: 12px;
        }

        .orders-bottom-actions .btn {
            width: 100%;
        }

    }
</style>


<?php

include("../includes/footer.php");

?>