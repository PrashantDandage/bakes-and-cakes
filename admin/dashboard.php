<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");

/* -----------------------------
   Dashboard Statistics
------------------------------*/

// Total Products
$product_query = "SELECT COUNT(*) AS total_products FROM products";
$product_result = mysqli_query($conn, $product_query);
$product_row = mysqli_fetch_assoc($product_result);
$total_products = $product_row['total_products'];

// Total Categories
$category_query = "SELECT COUNT(*) AS total_categories FROM categories";
$category_result = mysqli_query($conn, $category_query);
$category_row = mysqli_fetch_assoc($category_result);
$total_categories = $category_row['total_categories'];

// Available Products
$available_query = "SELECT COUNT(*) AS available_products
                    FROM products
                    WHERE status='Available'";
$available_result = mysqli_query($conn, $available_query);
$available_row = mysqli_fetch_assoc($available_result);
$available_products = $available_row['available_products'];

// Out Of Stock
$out_query = "SELECT COUNT(*) AS out_of_stock
              FROM products
              WHERE status='Out of Stock'";
$out_result = mysqli_query($conn, $out_query);
$out_row = mysqli_fetch_assoc($out_result);
$out_of_stock = $out_row['out_of_stock'];

// Total Customers
$customer_query = "SELECT COUNT(*) AS total_customers
                   FROM customers";
$customer_result = mysqli_query($conn, $customer_query);
$customer_row = mysqli_fetch_assoc($customer_result);
$total_customers = $customer_row['total_customers'];

// Total Orders
$order_query = "SELECT COUNT(*) AS total_orders
                FROM orders";
$order_result = mysqli_query($conn, $order_query);
$order_row = mysqli_fetch_assoc($order_result);
$total_orders = $order_row['total_orders'];

// Pending Orders
$pending_query = "SELECT COUNT(*) AS pending_orders
                  FROM orders
                  WHERE status='Pending'";
$pending_result = mysqli_query($conn, $pending_query);
$pending_row = mysqli_fetch_assoc($pending_result);
$pending_orders = $pending_row['pending_orders'];
?>

<div class="container mt-5">

    <h2 class="fw-bold mb-4">
        📊 Admin Dashboard
    </h2>

    <div class="row">

        <!-- Total Products -->
        <div class="col-md-3 mb-4">

            <div class="card bg-primary text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Products</h6>

                            <h2><?php echo $total_products; ?></h2>

                        </div>

                        <i class="bi bi-box-seam display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Categories -->
        <div class="col-md-3 mb-4">

            <div class="card bg-success text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Categories</h6>

                            <h2><?php echo $total_categories; ?></h2>

                        </div>

                        <i class="bi bi-grid-fill display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Available -->
        <div class="col-md-3 mb-4">

            <div class="card bg-info text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Available Products</h6>

                            <h2><?php echo $available_products; ?></h2>

                        </div>

                        <i class="bi bi-check-circle-fill display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Out Of Stock -->
        <div class="col-md-3 mb-4">

            <div class="card bg-danger text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Out Of Stock</h6>

                            <h2><?php echo $out_of_stock; ?></h2>

                        </div>

                        <i class="bi bi-x-circle-fill display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Customers -->
        <div class="col-md-4 mb-4">

            <div class="card bg-warning text-dark shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Customers</h6>

                            <h2><?php echo $total_customers; ?></h2>

                        </div>

                        <i class="bi bi-people-fill display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Orders -->
        <div class="col-md-4 mb-4">

            <div class="card bg-secondary text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Total Orders</h6>

                            <h2><?php echo $total_orders; ?></h2>

                        </div>

                        <i class="bi bi-cart-check-fill display-4"></i>

                    </div>

                </div>

            </div>

        </div>

        <!-- Pending Orders -->
        <div class="col-md-4 mb-4">

            <div class="card bg-dark text-white shadow border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h6>Pending Orders</h6>

                            <h2><?php echo $pending_orders; ?></h2>

                        </div>

                        <i class="bi bi-clock-history display-4"></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Quick Actions -->

    <div class="card shadow border-0 mt-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                Quick Actions

            </h5>

        </div>

        <div class="card-body">

            <a href="add-product.php" class="btn btn-primary me-2 mb-2">

                <i class="bi bi-plus-circle"></i>

                Add Product

            </a>

            <a href="view-products.php" class="btn btn-success me-2 mb-2">

                <i class="bi bi-eye"></i>

                View Products

            </a>

            <a href="add-category.php" class="btn btn-warning me-2 mb-2">

                <i class="bi bi-folder-plus"></i>

                Add Category

            </a>

            <a href="view-categories.php" class="btn btn-info text-white me-2 mb-2">

                <i class="bi bi-folder2-open"></i>

                View Categories

            </a>

            <a href="orders.php" class="btn btn-danger mb-2">

                <i class="bi bi-bag-check-fill"></i>

                Manage Orders

            </a>

        </div>

    </div>

</div>

</body>

</html>