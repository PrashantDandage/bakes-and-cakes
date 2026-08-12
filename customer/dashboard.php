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
$customer_email = $_SESSION['customer_email'] ?? '';


// =====================================================
// TOTAL ORDERS
// =====================================================

$order_count_query = "
    SELECT COUNT(*) AS total_orders
    FROM orders
    WHERE customer_id = '$customer_id'
";

$order_count_result = mysqli_query($conn, $order_count_query);

$order_count_row = mysqli_fetch_assoc($order_count_result);

$total_orders = $order_count_row['total_orders'] ?? 0;


// =====================================================
// TOTAL SPENT
// =====================================================

$total_spent_query = "
    SELECT COALESCE(SUM(total_amount), 0) AS total_spent
    FROM orders
    WHERE customer_id = '$customer_id'
    AND status != 'Cancelled'
";

$total_spent_result = mysqli_query($conn, $total_spent_query);

$total_spent_row = mysqli_fetch_assoc($total_spent_result);

$total_spent = $total_spent_row['total_spent'] ?? 0;


// =====================================================
// PENDING ORDERS
// =====================================================

$pending_query = "
    SELECT COUNT(*) AS pending_orders
    FROM orders
    WHERE customer_id = '$customer_id'
    AND status = 'Pending'
";

$pending_result = mysqli_query($conn, $pending_query);

$pending_row = mysqli_fetch_assoc($pending_result);

$pending_orders = $pending_row['pending_orders'] ?? 0;


// =====================================================
// CART ITEMS
// =====================================================

$cart_items = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    foreach ($_SESSION['cart'] as $quantity) {

        $cart_items += (int) $quantity;

    }

}


// =====================================================
// RECENT ORDERS
// =====================================================

$recent_orders_query = "
    SELECT id, total_amount, order_date, status
    FROM orders
    WHERE customer_id = '$customer_id'
    ORDER BY order_date DESC
    LIMIT 5
";

$recent_orders_result = mysqli_query(
    $conn,
    $recent_orders_query
);


// =====================================================
// HEADER + NAVBAR
// =====================================================

include("../includes/header.php");

include("../includes/navbar.php");

?>


<!-- =====================================================
     CUSTOMER DASHBOARD
====================================================== -->

<div class="container py-5">


    <!-- =================================================
         WELCOME SECTION
    ================================================== -->

    <div class="dashboard-welcome mb-5">

        <div>

            <h1 class="fw-bold">

                Welcome back,
                <span>
                    <?php echo htmlspecialchars($customer_name); ?>
                </span>
                👋

            </h1>

            <p class="text-muted mb-0">

                Manage your orders, cart and account from your dashboard.

            </p>

        </div>

    </div>


    <!-- =================================================
         STATISTICS
    ================================================== -->

    <div class="row g-4 mb-5">


        <!-- TOTAL ORDERS -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-card dashboard-blue">

                <div class="dashboard-card-content">

                    <div>

                        <p>Total Orders</p>

                        <h2>

                            <?php echo $total_orders; ?>

                        </h2>

                    </div>

                    <div class="dashboard-icon">

                        <i class="bi bi-bag-check-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- PENDING ORDERS -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-card dashboard-orange">

                <div class="dashboard-card-content">

                    <div>

                        <p>Pending Orders</p>

                        <h2>

                            <?php echo $pending_orders; ?>

                        </h2>

                    </div>

                    <div class="dashboard-icon">

                        <i class="bi bi-clock-fill"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- TOTAL SPENT -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-card dashboard-green">

                <div class="dashboard-card-content">

                    <div>

                        <p>Total Spent</p>

                        <h2>

                            ₹<?php echo number_format($total_spent, 2); ?>

                        </h2>

                    </div>

                    <div class="dashboard-icon">

                        <i class="bi bi-currency-rupee"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- CART ITEMS -->

        <div class="col-xl-3 col-lg-6 col-md-6">

            <div class="dashboard-card dashboard-red">

                <div class="dashboard-card-content">

                    <div>

                        <p>Cart Items</p>

                        <h2>

                            <?php echo $cart_items; ?>

                        </h2>

                    </div>

                    <div class="dashboard-icon">

                        <i class="bi bi-cart-fill"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         MAIN DASHBOARD AREA
    ================================================== -->

    <div class="row g-4">


        <!-- =================================================
             CUSTOMER PROFILE
        ================================================== -->

        <div class="col-lg-4">

            <div class="card dashboard-profile-card h-100">

                <div class="card-body p-4">


                    <div class="profile-avatar">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <h3 class="fw-bold text-center mt-3">

                        <?php echo htmlspecialchars($customer_name); ?>

                    </h3>


                    <p class="text-muted text-center">

                        <?php echo htmlspecialchars($customer_email); ?>

                    </p>


                    <hr>


                    <div class="profile-info">

                        <div class="profile-info-row">

                            <span>

                                <i class="bi bi-person"></i>

                                Name

                            </span>

                            <strong>

                                <?php echo htmlspecialchars($customer_name); ?>

                            </strong>

                        </div>


                        <div class="profile-info-row">

                            <span>

                                <i class="bi bi-envelope"></i>

                                Email

                            </span>

                            <strong>

                                <?php echo htmlspecialchars($customer_email); ?>

                            </strong>

                        </div>


                    </div>


                    <a href="home.php" class="btn btn-dark w-100 mt-4">

                        <i class="bi bi-house"></i>

                        Back to Home

                    </a>

                </div>

            </div>

        </div>


        <!-- =================================================
             QUICK ACTIONS
        ================================================== -->

        <div class="col-lg-8">

            <div class="card dashboard-actions-card">

                <div class="card-header">

                    <h4 class="mb-0 fw-bold">

                        <i class="bi bi-lightning-fill text-warning"></i>

                        Quick Actions

                    </h4>

                </div>


                <div class="card-body">

                    <div class="row g-3">


                        <!-- SHOP -->

                        <div class="col-md-6">

                            <a href="shop.php" class="dashboard-action">

                                <div class="action-icon action-blue">

                                    <i class="bi bi-shop"></i>

                                </div>

                                <div>

                                    <h5>
                                        Continue Shopping
                                    </h5>

                                    <p>
                                        Explore our bakery products
                                    </p>

                                </div>

                                <i class="bi bi-arrow-right ms-auto"></i>

                            </a>

                        </div>


                        <!-- MY ORDERS -->

                        <div class="col-md-6">

                            <a href="my-orders.php" class="dashboard-action">

                                <div class="action-icon action-green">

                                    <i class="bi bi-receipt"></i>

                                </div>

                                <div>

                                    <h5>
                                        My Orders
                                    </h5>

                                    <p>
                                        View your order history
                                    </p>

                                </div>

                                <i class="bi bi-arrow-right ms-auto"></i>

                            </a>

                        </div>


                        <!-- CART -->

                        <div class="col-md-6">

                            <a href="cart.php" class="dashboard-action">

                                <div class="action-icon action-orange">

                                    <i class="bi bi-cart3"></i>

                                </div>

                                <div>

                                    <h5>
                                        Shopping Cart
                                    </h5>

                                    <p>
                                        View items in your cart
                                    </p>

                                </div>

                                <i class="bi bi-arrow-right ms-auto"></i>

                            </a>

                        </div>


                        <!-- LOGOUT -->

                        <div class="col-md-6">

                            <a href="logout.php" class="dashboard-action">

                                <div class="action-icon action-red">

                                    <i class="bi bi-box-arrow-right"></i>

                                </div>

                                <div>

                                    <h5>
                                        Logout
                                    </h5>

                                    <p>
                                        Sign out of your account
                                    </p>

                                </div>

                                <i class="bi bi-arrow-right ms-auto"></i>

                            </a>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         RECENT ORDERS
    ================================================== -->

    <div class="card recent-orders-card mt-4">


        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0 fw-bold">

                <i class="bi bi-clock-history"></i>

                Recent Orders

            </h4>


            <a href="my-orders.php" class="btn btn-outline-dark btn-sm">

                View All

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>


        <div class="card-body p-0">

            <?php

            if (
                $recent_orders_result &&
                mysqli_num_rows($recent_orders_result) > 0
            ) {

                ?>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Order ID
                                </th>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Amount
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php

                            while (
                                $order =
                                mysqli_fetch_assoc($recent_orders_result)
                            ) {

                                $status = $order['status'];


                                // STATUS BADGE
                        
                                if ($status == 'Pending') {

                                    $status_class = 'bg-warning text-dark';

                                } elseif ($status == 'Confirmed') {

                                    $status_class = 'bg-primary';

                                } elseif ($status == 'Delivered') {

                                    $status_class = 'bg-success';

                                } elseif ($status == 'Cancelled') {

                                    $status_class = 'bg-danger';

                                } else {

                                    $status_class = 'bg-secondary';

                                }

                                ?>

                                <tr>

                                    <td>

                                        <strong>

                                            #<?php echo $order['id']; ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <?php

                                        echo date(
                                            "d M Y",
                                            strtotime($order['order_date'])
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <strong>

                                            ₹<?php

                                            echo number_format(
                                                $order['total_amount'],
                                                2
                                            );

                                            ?>

                                        </strong>

                                    </td>


                                    <td>

                                        <span class="badge <?php echo $status_class; ?>">

                                            <?php echo htmlspecialchars($status); ?>

                                        </span>

                                    </td>


                                    <td>

                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-dark">

                                            <i class="bi bi-eye"></i>

                                            View

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

                <div class="empty-orders text-center p-5">

                    <i class="bi bi-receipt display-4 text-muted"></i>

                    <h5 class="mt-3">

                        No Orders Yet

                    </h5>

                    <p class="text-muted">

                        You haven't placed any orders yet.

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

</div>


<!-- =====================================================
     DASHBOARD CSS
====================================================== -->

<style>
    .dashboard-welcome {
        background: linear-gradient(135deg,
                #fff7ed,
                #ffffff);

        border-radius: 20px;

        padding: 30px;

        border: 1px solid #f1e4d7;
    }

    .dashboard-welcome h1 {
        color: #2c1d12;
    }

    .dashboard-welcome h1 span {
        color: #d35400;
    }


    /* =====================================================
   STAT CARDS
===================================================== */

    .dashboard-card {
        border-radius: 20px;

        padding: 25px;

        color: white;

        min-height: 145px;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, 0.10);

        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);

        box-shadow:
            0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .dashboard-card-content {
        display: flex;

        justify-content: space-between;

        align-items: center;

        height: 100%;
    }

    .dashboard-card p {
        margin: 0 0 8px;

        font-weight: 600;

        opacity: 0.9;
    }

    .dashboard-card h2 {
        margin: 0;

        font-weight: 700;
    }

    .dashboard-icon {
        font-size: 45px;

        opacity: 0.85;
    }

    .dashboard-blue {
        background: #0d6efd;
    }

    .dashboard-orange {
        background: #fd7e14;
    }

    .dashboard-green {
        background: #198754;
    }

    .dashboard-red {
        background: #dc3545;
    }


    /* =====================================================
   PROFILE CARD
===================================================== */

    .dashboard-profile-card,
    .dashboard-actions-card,
    .recent-orders-card {
        border: none;

        border-radius: 20px;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, 0.08);

        overflow: hidden;
    }

    .profile-avatar {
        width: 90px;
        height: 90px;

        margin: auto;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #fff1e6;

        color: #d35400;

        font-size: 42px;
    }

    .profile-info-row {
        display: flex;

        justify-content: space-between;

        gap: 15px;

        padding: 12px 0;

        border-bottom: 1px solid #eeeeee;
    }

    .profile-info-row:last-child {
        border-bottom: none;
    }

    .profile-info-row span {
        color: #777;
    }

    .profile-info-row i {
        margin-right: 5px;
    }


    /* =====================================================
   QUICK ACTIONS
===================================================== */

    .dashboard-actions-card .card-header,
    .recent-orders-card .card-header {
        background: #ffffff;

        padding: 20px 25px;

        border-bottom: 1px solid #eeeeee;
    }

    .dashboard-action {
        display: flex;

        align-items: center;

        gap: 15px;

        padding: 18px;

        text-decoration: none;

        color: #222;

        background: #f8f9fa;

        border-radius: 15px;

        transition: all 0.3s ease;
    }

    .dashboard-action:hover {
        transform: translateY(-3px);

        background: #ffffff;

        box-shadow:
            0 8px 20px rgba(0, 0, 0, 0.08);

        color: #222;
    }

    .dashboard-action h5 {
        margin: 0 0 4px;

        font-weight: 700;
    }

    .dashboard-action p {
        margin: 0;

        color: #777;

        font-size: 14px;
    }

    .action-icon {
        min-width: 50px;
        width: 50px;
        height: 50px;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 14px;

        color: white;

        font-size: 22px;
    }

    .action-blue {
        background: #0d6efd;
    }

    .action-green {
        background: #198754;
    }

    .action-orange {
        background: #fd7e14;
    }

    .action-red {
        background: #dc3545;
    }


    /* =====================================================
   RECENT ORDERS
===================================================== */

    .recent-orders-card .table {
        margin: 0;
    }

    .recent-orders-card th {
        font-size: 14px;

        white-space: nowrap;
    }

    .recent-orders-card td {
        padding: 15px;

        white-space: nowrap;
    }


    /* =====================================================
   EMPTY ORDERS
===================================================== */

    .empty-orders {
        background: #ffffff;
    }

    .empty-orders i {
        opacity: 0.5;
    }


    /* =====================================================
   MOBILE
===================================================== */

    @media (max-width: 768px) {

        .dashboard-welcome {
            padding: 22px;
        }

        .dashboard-welcome h1 {
            font-size: 28px;
        }

        .dashboard-card {
            min-height: 130px;
        }

        .profile-info-row {
            flex-direction: column;

            gap: 5px;
        }

    }
</style>


<?php

include("../includes/footer.php");

?>