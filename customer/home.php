<?php
session_start();
include("../database/db.php");

include("../includes/header.php");
include("../includes/navbar.php");
?>

<!-- ================= HERO SECTION ================= -->

<section class="hero-section">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <span class="hero-tag">

                    Freshly Baked Every Day

                </span>

                <h1 class="hero-title">

                    Delicious Cakes <br>

                    Made With Love ❤️

                </h1>

                <p class="hero-text">

                    Celebrate every occasion with our freshly baked cakes,
                    pastries, cookies and desserts prepared using premium
                    ingredients.

                </p>

                <a href="shop.php" class="btn btn-dark btn-lg me-3">

                    Shop Now

                </a>

                <a href="about.php" class="btn btn-outline-dark btn-lg">

                    Learn More

                </a>

            </div>

            <div class="col-lg-6 text-center">

                <img src="../assets/images/hero-cake.jpg" class="hero-img img-fluid" alt="Hero Cake">

            </div>

        </div>

    </div>

</section>

<!-- ================= FEATURED PRODUCTS ================= -->

<section class="products-section py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Featured Products

            </h2>

            <p class="text-muted">

                Freshly baked and loved by our customers.

            </p>

        </div>

        <div class="row">

            <?php

            $query = "SELECT *
          FROM products
          WHERE status='Available'
          ORDER BY created_at DESC
          LIMIT 4";

            $result = mysqli_query($conn, $query);

            while ($row = mysqli_fetch_assoc($result)) {

                ?>

                <div class="col-lg-3 col-md-6 mb-4">

                    <div class="card product-card h-100">

                        <img src="../uploads/<?php echo $row['image']; ?>" class="card-img-top"
                            alt="<?php echo $row['name']; ?>">

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title">

                                <?php echo $row['name']; ?>

                            </h5>

                            <p class="text-muted small">

                                <?php echo substr($row['description'], 0, 70); ?>...

                            </p>

                            <h4 class="text-danger">

                                ₹<?php echo number_format($row['price'], 2); ?>

                            </h4>

                            <a href="product-details.php?id=<?php echo $row['id']; ?>" class="btn btn-dark mt-auto">

                                View Details

                            </a>

                        </div>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>

<!-- ================= CATEGORIES ================= -->

<section class="py-5 bg-light">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Explore Categories

            </h2>

        </div>

        <div class="row text-center">

            <?php

            $category_query = "SELECT * FROM categories ORDER BY category_name";

            $category_result = mysqli_query($conn, $category_query);

            while ($category = mysqli_fetch_assoc($category_result)) {

                ?>

                <div class="col-md-3 mb-4">

                    <div class="feature-box">

                        <h1>

                            🎂

                        </h1>

                        <h4>

                            <?php echo $category['category_name']; ?>

                        </h4>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</section>

<!-- ================= WHY CHOOSE US ================= -->

<section class="why-section py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                Why Choose Us

            </h2>

        </div>

        <div class="row">

            <div class="col-md-3 mb-4">

                <div class="feature-box text-center">

                    <div class="feature-icon">

                        🍰

                    </div>

                    <h4>

                        Fresh Cakes

                    </h4>

                    <p>

                        Freshly baked every morning.

                    </p>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="feature-box text-center">

                    <div class="feature-icon">

                        🚚

                    </div>

                    <h4>

                        Fast Delivery

                    </h4>

                    <p>

                        Quick doorstep delivery.

                    </p>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="feature-box text-center">

                    <div class="feature-icon">

                        ❤️

                    </div>

                    <h4>

                        Premium Quality

                    </h4>

                    <p>

                        Finest ingredients only.

                    </p>

                </div>

            </div>

            <div class="col-md-3 mb-4">

                <div class="feature-box text-center">

                    <div class="feature-icon">

                        ⭐

                    </div>

                    <h4>

                        Customer Satisfaction

                    </h4>

                    <p>

                        Thousands of happy customers.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= TESTIMONIALS ================= -->

<section class="py-5">

    <div class="container">

        <div class="text-center mb-5">

            <h2 class="fw-bold">

                What Our Customers Say

            </h2>

        </div>

        <div class="row">

            <div class="col-md-4">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <h2>⭐⭐⭐⭐⭐</h2>

                        <p>

                            The cakes are always fresh and delicious!

                        </p>

                        <strong>

                            — Rahul

                        </strong>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <h2>⭐⭐⭐⭐⭐</h2>

                        <p>

                            Beautiful cakes and excellent service.

                        </p>

                        <strong>

                            — Sneha

                        </strong>

                    </div>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <h2>⭐⭐⭐⭐⭐</h2>

                        <p>

                            Highly recommended bakery in the city.

                        </p>

                        <strong>

                            — Amit

                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================= CALL TO ACTION ================= -->

<section class="py-5 text-center bg-dark text-white">

    <div class="container">

        <h2>

            Ready To Order Your Favourite Cake?

        </h2>

        <p>

            Fresh • Delicious • Made With Love

        </p>

        <a href="shop.php" class="btn btn-warning btn-lg">

            Order Now

        </a>

    </div>

</section>

<?php include("../includes/footer.php"); ?>