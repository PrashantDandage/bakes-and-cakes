<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/db.php");

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- =====================================================
         BREADCRUMB
    ====================================================== -->

    <nav aria-label="breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="home.php">
                    Home
                </a>

            </li>

            <li class="breadcrumb-item active" aria-current="page">

                Shop

            </li>

        </ol>

    </nav>


    <!-- =====================================================
         SHOP HEADING
    ====================================================== -->

    <div class="text-center mb-5">

        <h1 class="display-4 fw-bold">

            <i class="bi bi-bag-heart-fill text-warning"></i>

            Our Bakery Products

        </h1>

        <p class="lead text-muted">

            Freshly baked cakes, pastries, cookies and much more.

        </p>

    </div>


    <!-- =====================================================
         SEARCH & FILTER SECTION
    ====================================================== -->

    <div class="card shadow-sm border-0 rounded-4 mb-5">

        <div class="card-body p-4">

            <div class="row align-items-center">

                <!-- Search -->

                <div class="col-lg-5 col-md-12 mb-3 mb-lg-0">

                    <div class="input-group">

                        <span class="input-group-text bg-white">

                            <i class="bi bi-search"></i>

                        </span>

                        <input type="text" class="form-control" placeholder="Search delicious cakes...">

                    </div>

                </div>


                <!-- Category -->

                <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">

                    <select class="form-select">

                        <option value="">

                            All Categories

                        </option>

                        <?php

                        $category_query = "
                            SELECT *
                            FROM categories
                            ORDER BY category_name ASC
                        ";

                        $category_result = mysqli_query(
                            $conn,
                            $category_query
                        );

                        if ($category_result) {

                            while (
                                $category =
                                mysqli_fetch_assoc($category_result)
                            ) {

                                ?>

                                <option value="<?php echo htmlspecialchars($category['category_name']); ?>">

                                    <?php echo htmlspecialchars($category['category_name']); ?>

                                </option>

                                <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <!-- Sort -->

                <div class="col-lg-3 col-md-6">

                    <select class="form-select">

                        <option value="newest">

                            Newest First

                        </option>

                        <option value="low-high">

                            Price : Low to High

                        </option>

                        <option value="high-low">

                            Price : High to Low

                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>


    <!-- =====================================================
         PRODUCTS
    ====================================================== -->

    <div class="row g-4">

        <?php

        $query = "
            SELECT *
            FROM products
            WHERE status = 'Available'
            ORDER BY created_at DESC
        ";

        $result = mysqli_query($conn, $query);


        if ($result && mysqli_num_rows($result) > 0) {

            while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <!-- =================================================
                     PRODUCT COLUMN
                ================================================== -->

                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">


                    <!-- PRODUCT CARD -->

                    <div class="card product-card h-100">


                        <!-- =================================================
                             PRODUCT IMAGE
                        ================================================== -->

                        <div class="product-image-wrapper">

                            <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" class="product-image"
                                alt="<?php echo htmlspecialchars($row['name']); ?>">


                            <!-- Wishlist -->

                            <div class="wishlist-icon">

                                <i class="bi bi-heart-fill"></i>

                            </div>

                        </div>


                        <!-- =================================================
                             PRODUCT CONTENT
                        ================================================== -->

                        <div class="card-body d-flex flex-column">


                            <!-- Category -->

                            <span class="badge bg-warning text-dark align-self-start mb-2">

                                <i class="bi bi-tag-fill"></i>

                                <?php echo htmlspecialchars($row['category']); ?>

                            </span>


                            <!-- Product Name -->

                            <h4 class="product-name">

                                <?php echo htmlspecialchars($row['name']); ?>

                            </h4>


                            <!-- Rating -->

                            <div class="product-rating mb-2">

                                <i class="bi bi-star-fill"></i>

                                <i class="bi bi-star-fill"></i>

                                <i class="bi bi-star-fill"></i>

                                <i class="bi bi-star-fill"></i>

                                <i class="bi bi-star-fill"></i>

                                <span class="text-muted ms-1">
                                    5.0
                                </span>

                            </div>


                            <!-- Description -->

                            <p class="text-muted product-description">

                                <?php

                                $description =
                                    $row['description'];

                                if (strlen($description) > 80) {

                                    echo htmlspecialchars(
                                        substr($description, 0, 80)
                                    ) . "...";

                                } else {

                                    echo htmlspecialchars(
                                        $description
                                    );

                                }

                                ?>

                            </p>


                            <!-- Fresh Badge -->

                            <span class="badge bg-success align-self-start mb-3">

                                <i class="bi bi-check-circle-fill"></i>

                                Freshly Baked

                            </span>


                            <!-- =================================================
                                 PRICE & BUTTONS
                            ================================================== -->

                            <div class="mt-auto">


                                <!-- Price -->

                                <h3 class="product-price">

                                    ₹<?php echo number_format(
                                        $row['price'],
                                        2
                                    ); ?>

                                </h3>


                                <!-- View Details -->

                                <a href="product-details.php?id=<?php echo $row['id']; ?>" class="btn btn-dark w-100 mb-2">

                                    <i class="bi bi-eye"></i>

                                    View Details

                                </a>


                                <!-- Add To Cart -->

                                <form action="add-to-cart.php" method="POST">

                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">

                                    <input type="hidden" name="quantity" value="1">

                                    <button type="submit" class="btn btn-success w-100">

                                        <i class="bi bi-cart-plus"></i>

                                        Add To Cart

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

            <!-- =================================================
                 NO PRODUCTS
            ================================================== -->

            <div class="col-12">

                <div class="alert alert-warning text-center shadow-sm">

                    <h4 class="mb-0">

                        <i class="bi bi-exclamation-circle"></i>

                        No Products Available

                    </h4>

                    <p class="mb-0 mt-2">

                        Please check back later for our freshly baked products.

                    </p>

                </div>

            </div>

            <?php

        }

        ?>

    </div>

</div>


<?php

include("../includes/footer.php");

?>