<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("../database/db.php");

// Check if Product ID exists
if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit();
}

$product_id = intval($_GET['id']);

// Get Product
$query = "SELECT * FROM products WHERE id='$product_id' AND status='Available'";
$result = mysqli_query($conn, $query);

// Product not found
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: shop.php");
    exit();
}

$product = mysqli_fetch_assoc($result);

include("../includes/header.php");
include("../includes/navbar.php");
?>

<section class="product-details-section">

    <div class="container">

        <!-- Breadcrumb -->
        <nav class="details-breadcrumb mb-4">

            <a href="home.php">Home</a>

            <span> / </span>

            <a href="shop.php">Shop</a>

            <span> / </span>

            <span><?php echo htmlspecialchars($product['name']); ?></span>

        </nav>

        <div class="row">

            <!-- Product Image -->
            <div class="col-lg-6 mb-4">

                <div class="product-image-box">

                    <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>"
                        alt="<?php echo htmlspecialchars($product['name']); ?>">

                </div>

            </div>

            <!-- Product Details -->
            <div class="col-lg-6">

                <div class="product-info">

                    <!-- Category -->

                    <span class="category">

                        <?php echo htmlspecialchars($product['category']); ?>

                    </span>

                    <!-- Product Name -->

                    <h2>

                        <?php echo htmlspecialchars($product['name']); ?>

                    </h2>

                    <!-- Price -->

                    <div class="price">

                        ₹<?php echo number_format($product['price'], 2); ?>

                    </div>

                    <!-- Description -->

                    <p>

                        <?php echo nl2br(htmlspecialchars($product['description'])); ?>

                    </p>

                    <!-- Features -->

                    <ul class="product-features">

                        <li>✔ Freshly Prepared Every Day</li>

                        <li>✔ Premium Quality Ingredients</li>

                        <li>✔ Safe & Hygienic Packaging</li>

                    </ul>

                    <!-- Add To Cart Form -->

                    <form action="add-to-cart.php" method="POST">

                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                        <div class="quantity-box">

                            <label for="quantity">

                                Quantity

                            </label>

                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1"
                                max="10">

                        </div>

                        <div class="details-buttons">

                            <button type="submit" class="btn-cart">

                                🛒 Add to Cart

                            </button>

                            <a href="shop.php" class="btn-back text-decoration-none text-center">

                                ← Back to Shop

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <!-- Description Section -->

        <div class="description-box">

            <h3>

                Product Description

            </h3>

            <p>

                <?php echo nl2br(htmlspecialchars($product['description'])); ?>

            </p>

        </div>

    </div>

</section>

<?php
include("../includes/footer.php");
?>