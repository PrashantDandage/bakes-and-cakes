<?php
session_start();
include("../database/db.php");

include("../includes/header.php");
include("../includes/navbar.php");

$total = 0;
?>

<div class="container py-5">

    <h2 class="mb-4 fw-bold">
        🛒 Shopping Cart
    </h2>

    <?php

    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

        ?>

        <div class="alert alert-warning">

            Your shopping cart is empty.

        </div>

        <a href="shop.php" class="btn btn-primary">

            ← Continue Shopping

        </a>

        <?php

    } else {

        ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle text-center">

                <thead class="table-dark">

                    <tr>

                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th width="120">Quantity</th>
                        <th>Subtotal</th>
                        <th width="180">Action</th>

                    </tr>

                </thead>

                <tbody>

                    <?php

                    foreach ($_SESSION['cart'] as $product_id => $quantity) {

                        $query = "SELECT * FROM products WHERE id='$product_id'";
                        $result = mysqli_query($conn, $query);

                        if ($product = mysqli_fetch_assoc($result)) {

                            $subtotal = $product['price'] * $quantity;
                            $total += $subtotal;

                            ?>

                            <tr>

                                <!-- Product Image -->
                                <td>

                                    <img src="../uploads/<?php echo $product['image']; ?>" width="90" class="img-fluid rounded">

                                </td>

                                <!-- Product Name -->
                                <td>

                                    <?php echo $product['name']; ?>

                                </td>

                                <!-- Product Price -->
                                <td>

                                    ₹<?php echo number_format($product['price'], 2); ?>

                                </td>

                                <!-- Quantity + Update -->
                                <td>

                                    <form action="update-cart.php" method="POST">

                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                                        <input type="number" name="quantity" value="<?php echo $quantity; ?>" min="1"
                                            class="form-control text-center mb-2">

                                        <button type="submit" class="btn btn-primary btn-sm w-100">

                                            <i class="bi bi-arrow-repeat"></i>

                                            Update

                                        </button>

                                </td>

                                <!-- Subtotal -->
                                <td>

                                    ₹<?php echo number_format($subtotal, 2); ?>

                                </td>

                                <!-- Remove -->
                                <td>

                                    <a href="remove-from-cart.php?id=<?php echo $product['id']; ?>"
                                        class="btn btn-danger btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to remove this product?')">

                                        <i class="bi bi-trash"></i>

                                        Remove

                                    </a>

                                    </form>

                                </td>

                            </tr>

                            <?php

                        }

                    }

                    ?>

                </tbody>

            </table>

        </div>

        <div class="row mt-4">

            <div class="col-md-12 text-end">

                <h3>

                    Grand Total :

                    <span class="text-danger">

                        ₹<?php echo number_format($total, 2); ?>

                    </span>

                </h3>

                <a href="shop.php" class="btn btn-secondary">

                    ← Continue Shopping

                </a>

                <a href="checkout.php" class="btn btn-success">

                    Proceed to Checkout →

                </a>

            </div>

        </div>

        <?php

    }

    ?>

</div>

<?php include("../includes/footer.php"); ?>