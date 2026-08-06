<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/db.php");
?>

<?php include("../includes/header.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="container py-5">

    <!-- Breadcrumb -->

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="home.php">Home</a>
            </li>

            <li class="breadcrumb-item active">
                Shop
            </li>

        </ol>

    </nav>

    <!-- Page Heading -->

    <div class="text-center mb-5">

        <h1 class="page-title">
            Our Bakery Products
        </h1>

        <p class="page-subtitle">
            Explore our freshly baked cakes, pastries, cookies and more.
        </p>

    </div>

    <div class="row g-4">

        <?php

        $query = "SELECT * FROM products
          WHERE status='Available'
          ORDER BY created_at DESC";

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <div class="col-lg-4 col-md-6">

                    <div class="card product-card h-100">

                        <img src="../uploads/<?php echo $row['image']; ?>" class="card-img-top"
                            alt="<?php echo $row['name']; ?>">

                        <div class="card-body d-flex flex-column">

                            <h4 class="product-name">

                                <?php echo $row['name']; ?>

                            </h4>

                            <p class="text-muted">

                                <?php echo $row['description']; ?>

                            </p>

                            <span class="product-category">

                                <?php echo $row['category']; ?>

                            </span>

                            <div class="mt-auto">

                                <h3 class="product-price">

                                    ₹<?php echo number_format($row['price'], 2); ?>

                                </h3>

                                <!-- View Details Button -->
                                <a href="product-details.php?id=<?php echo $row['id']; ?>" class="btn btn-custom w-100 mb-2">

                                    👁 View Details

                                </a>

                                <!-- Add To Cart Form -->
                                <form action="add-to-cart.php" method="POST">

                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="btn btn-success w-100">

                                        🛒 Add To Cart

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

                <?php

            }

        } else {

            ?>

            <div class="col-12">

                <div class="alert alert-warning text-center">

                    <h4>

                        No Products Available

                    </h4>

                </div>

            </div>

            <?php

        }

        ?>

    </div>

</div>

<?php include("../includes/footer.php"); ?>