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
// FETCH CUSTOMER DETAILS
// ======================================================

$customer_stmt = mysqli_prepare(
    $conn,
    "SELECT id, name, email, phone, address
     FROM customers
     WHERE id = ?
     LIMIT 1"
);

if (!$customer_stmt) {

    die("Database error. Please try again.");

}

mysqli_stmt_bind_param(
    $customer_stmt,
    "i",
    $customer_id
);

mysqli_stmt_execute($customer_stmt);

$customer_result = mysqli_stmt_get_result($customer_stmt);


if (!$customer_result || mysqli_num_rows($customer_result) === 0) {

    mysqli_stmt_close($customer_stmt);

    session_destroy();

    header("Location: login.php");
    exit();

}

$customer = mysqli_fetch_assoc($customer_result);

mysqli_stmt_close($customer_stmt);


// ======================================================
// CALCULATE ORDER TOTAL
// ======================================================

$total = 0;

$cart_products = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {

    $product_id = (int) $product_id;
    $quantity = (int) $quantity;


    // --------------------------------------------------
    // Validate quantity
    // --------------------------------------------------

    if ($quantity <= 0) {
        continue;
    }


    // --------------------------------------------------
    // Get current product information
    // --------------------------------------------------

    $product_stmt = mysqli_prepare(
        $conn,
        "SELECT id, name, price, status
         FROM products
         WHERE id = ?
         LIMIT 1"
    );


    if (!$product_stmt) {

        continue;

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


    // --------------------------------------------------
    // Skip unavailable/missing products
    // --------------------------------------------------

    if (!$product) {
        continue;
    }


    if ($product['status'] !== 'Available') {
        continue;
    }


    // --------------------------------------------------
    // Calculate subtotal using database price
    // --------------------------------------------------

    $price = (float) $product['price'];

    $subtotal = $price * $quantity;

    $total += $subtotal;


    // Store product information for display

    $cart_products[] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $price,
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];

}


// ======================================================
// CHECK VALID ORDER TOTAL
// ======================================================

if ($total <= 0 || empty($cart_products)) {

    header("Location: cart.php");
    exit();

}


// ======================================================
// HEADER + NAVBAR
// ======================================================

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Page Heading -->

    <div class="mb-4">

        <h2 class="fw-bold">

            Checkout

        </h2>

        <p class="text-muted mb-0">

            Review your details and order before placing it.

        </p>

    </div>


    <div class="row g-4">


        <!-- ==================================================
             CUSTOMER DETAILS
             ================================================== -->

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-dark text-white">

                    <h5 class="mb-0">

                        Customer Details

                    </h5>

                </div>


                <div class="card-body">


                    <!-- Name -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Name

                        </label>

                        <input type="text" class="form-control"
                            value="<?php echo htmlspecialchars($customer['name']); ?>" readonly>

                    </div>


                    <!-- Email -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Email

                        </label>

                        <input type="email" class="form-control"
                            value="<?php echo htmlspecialchars($customer['email']); ?>" readonly>

                    </div>


                    <!-- Phone -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Phone

                        </label>

                        <input type="text" class="form-control"
                            value="<?php echo htmlspecialchars($customer['phone']); ?>" readonly>

                    </div>


                    <!-- Address -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Delivery Address

                        </label>

                        <textarea class="form-control" rows="4"
                            readonly><?php echo htmlspecialchars($customer['address']); ?></textarea>

                    </div>


                    <!-- Profile Link -->

                    <a href="profile.php" class="btn btn-outline-success">

                        <i class="bi bi-pencil"></i>

                        Update Profile

                    </a>


                </div>

            </div>

        </div>


        <!-- ==================================================
             ORDER SUMMARY
             ================================================== -->

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        Order Summary

                    </h5>

                </div>


                <div class="card-body">


                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead>

                                <tr>

                                    <th>
                                        Product
                                    </th>

                                    <th class="text-center">
                                        Qty
                                    </th>

                                    <th class="text-end">
                                        Total
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php foreach ($cart_products as $item) { ?>

                                    <tr>

                                        <td>

                                            <strong>

                                                <?php

                                                echo htmlspecialchars(
                                                    $item['name']
                                                );

                                                ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                ₹<?php

                                                echo number_format(
                                                    $item['price'],
                                                    2
                                                );

                                                ?>

                                                each

                                            </small>

                                        </td>


                                        <td class="text-center">

                                            <?php

                                            echo (int) $item['quantity'];

                                            ?>

                                        </td>


                                        <td class="text-end">

                                            ₹<?php

                                            echo number_format(
                                                $item['subtotal'],
                                                2
                                            );

                                            ?>

                                        </td>

                                    </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>


                    <hr>


                    <!-- Grand Total -->

                    <div class="d-flex justify-content-between align-items-center">

                        <h4 class="mb-0">

                            Grand Total

                        </h4>

                        <h4 class="text-danger fw-bold mb-0">

                            ₹<?php

                            echo number_format(
                                $total,
                                2
                            );

                            ?>

                        </h4>

                    </div>


                    <!-- Place Order -->

                    <form action="place-order.php" method="POST">

                        <button type="submit" class="btn btn-success btn-lg w-100 mt-4">

                            <i class="bi bi-check-circle"></i>

                            Place Order

                        </button>

                    </form>


                    <!-- Back to Cart -->

                    <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2">

                        <i class="bi bi-cart"></i>

                        Back to Cart

                    </a>


                </div>

            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>