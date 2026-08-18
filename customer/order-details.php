<?php

session_start();

include("../database/db.php");

/*
|--------------------------------------------------------------------------
| Customer Login Check
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['customer_id'])) {

    header("Location: login.php");
    exit();

}

/*
|--------------------------------------------------------------------------
| Check Order ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    header("Location: my-orders.php");
    exit();

}

$order_id = (int) $_GET['id'];
$customer_id = (int) $_SESSION['customer_id'];

/*
|--------------------------------------------------------------------------
| Get Order
| Only allow the logged-in customer to view their own order
|--------------------------------------------------------------------------
*/

$stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM orders
     WHERE id = ?
     AND customer_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "ii",
    $order_id,
    $customer_id
);

mysqli_stmt_execute($stmt);

$order_result = mysqli_stmt_get_result($stmt);

/*
|--------------------------------------------------------------------------
| Order Not Found
|--------------------------------------------------------------------------
*/

if (!$order_result || mysqli_num_rows($order_result) === 0) {

    header("Location: my-orders.php");
    exit();

}

/*
|--------------------------------------------------------------------------
| Fetch Order
|--------------------------------------------------------------------------
*/

$order = mysqli_fetch_assoc($order_result);

mysqli_stmt_close($stmt);

/*
|--------------------------------------------------------------------------
| Load Header & Navbar
|--------------------------------------------------------------------------
*/

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Page Breadcrumb -->

    <nav aria-label="breadcrumb" class="mb-4">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="home.php">
                    Home
                </a>

            </li>

            <li class="breadcrumb-item">

                <a href="my-orders.php">
                    My Orders
                </a>

            </li>

            <li class="breadcrumb-item active" aria-current="page">

                Order #<?php echo htmlspecialchars($order['id']); ?>

            </li>

        </ol>

    </nav>


    <!-- Order Details Card -->

    <div class="card shadow border-0 rounded-4">

        <!-- Header -->

        <div class="card-header bg-dark text-white rounded-top-4">

            <div class="d-flex justify-content-between align-items-center">

                <h3 class="mb-0">

                    🧾 Order #<?php echo htmlspecialchars($order['id']); ?>

                </h3>

                <span class="badge bg-success">

                    <?php echo htmlspecialchars($order['status']); ?>

                </span>

            </div>

        </div>


        <!-- Body -->

        <div class="card-body p-4">


            <!-- Order Summary -->

            <div class="row mb-4">

                <!-- Date -->

                <div class="col-md-4 mb-3">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted">
                            Order Date
                        </small>

                        <h5 class="mt-2 mb-0">

                            <?php

                            echo date(
                                "d M Y",
                                strtotime($order['order_date'])
                            );

                            ?>

                        </h5>

                    </div>

                </div>


                <!-- Status -->

                <div class="col-md-4 mb-3">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted">
                            Order Status
                        </small>

                        <h5 class="mt-2 mb-0 text-success">

                            <?php echo htmlspecialchars($order['status']); ?>

                        </h5>

                    </div>

                </div>


                <!-- Total -->

                <div class="col-md-4 mb-3">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted">
                            Total Amount
                        </small>

                        <h5 class="mt-2 mb-0 text-danger">

                            ₹<?php

                            echo number_format(
                                $order['total_amount'],
                                2
                            );

                            ?>

                        </h5>

                    </div>

                </div>

            </div>


            <!-- Products Heading -->

            <h4 class="mb-3">

                🛒 Ordered Products

            </h4>


            <!-- Order Items Table -->

            <div class="table-responsive">

                <table class="table table-bordered text-center align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Image
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Quantity
                            </th>

                            <th>
                                Total
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php

                        /*
                        |--------------------------------------------------------------------------
                        | Get Order Items
                        |--------------------------------------------------------------------------
                        */

                        $item_stmt = mysqli_prepare(
                            $conn,
                            "SELECT
                                order_items.*,
                                products.name,
                                products.image
                             FROM order_items
                             INNER JOIN products
                             ON order_items.product_id = products.id
                             WHERE order_items.order_id = ?"
                        );

                        mysqli_stmt_bind_param(
                            $item_stmt,
                            "i",
                            $order_id
                        );

                        mysqli_stmt_execute($item_stmt);

                        $items_result = mysqli_stmt_get_result($item_stmt);


                        if ($items_result && mysqli_num_rows($items_result) > 0) {

                            while ($item = mysqli_fetch_assoc($items_result)) {

                                $item_total =
                                    $item['price'] *
                                    $item['quantity'];

                                ?>

                                <tr>

                                    <!-- Product Image -->

                                    <td>

                                        <img src="../uploads/<?php echo htmlspecialchars($item['image']); ?>"
                                            alt="<?php echo htmlspecialchars($item['name']); ?>" width="80" height="80"
                                            class="rounded" style="object-fit: cover;">

                                    </td>


                                    <!-- Product Name -->

                                    <td>

                                        <strong>

                                            <?php

                                            echo htmlspecialchars(
                                                $item['name']
                                            );

                                            ?>

                                        </strong>

                                    </td>


                                    <!-- Price -->

                                    <td>

                                        ₹<?php

                                        echo number_format(
                                            $item['price'],
                                            2
                                        );

                                        ?>

                                    </td>


                                    <!-- Quantity -->

                                    <td>

                                        <?php

                                        echo (int) $item['quantity'];

                                        ?>

                                    </td>


                                    <!-- Item Total -->

                                    <td>

                                        <strong>

                                            ₹<?php

                                            echo number_format(
                                                $item_total,
                                                2
                                            );

                                            ?>

                                        </strong>

                                    </td>

                                </tr>

                                <?php

                            }

                        } else {

                            ?>

                            <tr>

                                <td colspan="5" class="text-muted py-4">

                                    No products found for this order.

                                </td>

                            </tr>

                            <?php

                        }

                        mysqli_stmt_close($item_stmt);

                        ?>

                    </tbody>

                </table>

            </div>


            <!-- Bottom Actions -->

            <div class="d-flex justify-content-between align-items-center mt-4">

                <a href="my-orders.php" class="btn btn-secondary rounded-pill px-4">

                    ← Back To My Orders

                </a>

                <a href="shop.php" class="btn btn-success rounded-pill px-4">

                    🛒 Continue Shopping

                </a>

            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>