<?php



include("../database/db.php");

include("../includes/header.php");
include("../includes/navbar.php");


// =====================================================
// GET PRODUCT ID
// =====================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo '
        <div class="container py-5">
            <div class="alert alert-danger text-center">
                <h4>Invalid Product</h4>
                <p>Product information could not be found.</p>
                <a href="shop.php" class="btn btn-dark">
                    ← Back to Shop
                </a>
            </div>
        </div>
    ';

    include("../includes/footer.php");

    exit();
}


$product_id = (int) $_GET['id'];


// =====================================================
// FETCH PRODUCT
// =====================================================

$query = "
    SELECT *
    FROM products
    WHERE id = '$product_id'
    AND status = 'Available'
    LIMIT 1
";

$result = mysqli_query($conn, $query);


if (!$result || mysqli_num_rows($result) == 0) {

    echo '
        <div class="container py-5">
            <div class="alert alert-warning text-center">
                <h4>Product Not Found</h4>
                <p>This product is no longer available.</p>

                <a href="shop.php" class="btn btn-dark">
                    ← Back to Shop
                </a>
            </div>
        </div>
    ';

    include("../includes/footer.php");

    exit();
}


$product = mysqli_fetch_assoc($result);

?>

<div class="container py-5">

    <!-- =====================================================
         BREADCRUMB
    ====================================================== -->

    <nav aria-label="breadcrumb" class="mb-4">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="home.php">
                    Home
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="shop.php">
                    Shop
                </a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">

                <?php echo htmlspecialchars($product['name']); ?>

            </li>

        </ol>

    </nav>


    <!-- =====================================================
         PRODUCT DETAILS
    ====================================================== -->

    <div class="row g-5 align-items-center">


        <!-- =================================================
             PRODUCT IMAGE
        ================================================== -->

        <div class="col-lg-6">

            <div class="product-details-image-wrapper">

                <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>"
                    alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-details-image">

            </div>

        </div>


        <!-- =================================================
             PRODUCT INFORMATION
        ================================================== -->

        <div class="col-lg-6">

            <!-- Category -->

            <span class="badge bg-warning text-dark rounded-pill px-3 py-2 mb-3">

                <i class="bi bi-tag-fill"></i>

                <?php echo htmlspecialchars($product['category']); ?>

            </span>


            <!-- Product Name -->

            <h1 class="product-details-title">

                <?php echo htmlspecialchars($product['name']); ?>

            </h1>


            <!-- Rating -->

            <div class="product-details-rating mb-3">

                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>

                <span class="text-muted ms-2">
                    5.0
                </span>

            </div>


            <!-- Price -->

            <h2 class="product-details-price">

                ₹<?php echo number_format($product['price'], 2); ?>

            </h2>


            <!-- Freshly Baked -->

            <div class="mb-4">

                <span class="badge bg-success rounded-pill px-3 py-2">

                    <i class="bi bi-check-circle-fill"></i>

                    Freshly Baked

                </span>

            </div>


            <!-- Description -->

            <h5 class="fw-bold mb-2">

                Product Description

            </h5>

            <p class="text-muted product-details-description">

                <?php echo nl2br(htmlspecialchars($product['description'])); ?>

            </p>


            <hr class="my-4">


            <!-- =================================================
                 ADD TO CART
            ================================================== -->

            <form action="add-to-cart.php" method="POST">

                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">


                <div class="row align-items-end g-3">


                    <!-- Quantity -->

                    <div class="col-md-4">

                        <label for="quantity" class="form-label fw-bold">
                            Quantity
                        </label>

                        <input type="number" id="quantity" name="quantity" value="1" min="1"
                            class="form-control form-control-lg text-center" required>

                    </div>


                    <!-- Add To Cart -->

                    <div class="col-md-8">

                        <button type="submit" class="btn btn-success btn-lg w-100">

                            <i class="bi bi-cart-plus"></i>

                            Add To Cart

                        </button>

                    </div>

                </div>

            </form>


            <!-- Back to Shop -->

            <a href="shop.php" class="btn btn-outline-dark btn-lg w-100 mt-3">

                <i class="bi bi-arrow-left"></i>

                Back to Shop

            </a>


            <!-- Product Information -->

            <div class="product-details-features mt-4">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="feature-box text-center">

                            <i class="bi bi-basket"></i>

                            <small>
                                Freshly Baked
                            </small>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="feature-box text-center">

                            <i class="bi bi-box-seam"></i>

                            <small>
                                Quality Product
                            </small>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="feature-box text-center">

                            <i class="bi bi-heart"></i>

                            <small>
                                Made With Love
                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<style>
    /* =========================================================
   PRODUCT DETAILS PAGE
========================================================= */

    .product-details-image-wrapper {
        width: 100%;
        height: 520px;
        border-radius: 25px;
        overflow: hidden;
        background: #f8f8f8;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.10);
    }

    .product-details-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s ease;
    }

    .product-details-image-wrapper:hover .product-details-image {
        transform: scale(1.04);
    }


    /* PRODUCT TITLE */

    .product-details-title {
        font-size: 44px;
        font-weight: 700;
        color: #2c1d12;
        margin-bottom: 12px;
    }


    /* RATING */

    .product-details-rating {
        color: #ffc107;
        font-size: 20px;
    }

    .product-details-rating .text-muted {
        font-size: 16px;
    }


    /* PRICE */

    .product-details-price {
        color: #dc3545;
        font-size: 36px;
        font-weight: 700;
        margin-bottom: 20px;
    }


    /* DESCRIPTION */

    .product-details-description {
        font-size: 17px;
        line-height: 1.8;
    }


    /* FEATURE BOX */

    .feature-box {
        padding: 18px 10px;
        background: #f8f9fa;
        border-radius: 15px;
        height: 100%;
    }

    .feature-box i {
        display: block;
        font-size: 25px;
        margin-bottom: 8px;
        color: #dc3545;
    }

    .feature-box small {
        font-weight: 600;
        color: #555;
    }


    /* BUTTONS */

    .product-details-page .btn {
        border-radius: 30px;
    }


    /* MOBILE */

    @media (max-width: 768px) {

        .product-details-image-wrapper {
            height: 350px;
        }

        .product-details-title {
            font-size: 32px;
        }

        .product-details-price {
            font-size: 30px;
        }

    }
</style>


<?php

include("../includes/footer.php");

?>