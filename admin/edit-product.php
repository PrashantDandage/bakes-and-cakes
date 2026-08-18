<?php

session_start();


// ======================================================
// ADMIN LOGIN CHECK
// ======================================================

if (!isset($_SESSION['admin_email'])) {

    header("Location: login.php");
    exit();

}


// ======================================================
// DATABASE CONNECTION
// ======================================================

include("../database/db.php");


// ======================================================
// CHECK PRODUCT ID
// ======================================================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {

    echo "Invalid Product ID.";
    exit();

}

$id = (int) $_GET['id'];


// ======================================================
// FETCH PRODUCT
// ======================================================

$product_stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM products
     WHERE id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $product_stmt,
    "i",
    $id
);

mysqli_stmt_execute($product_stmt);

$product_result = mysqli_stmt_get_result($product_stmt);


if (!$product_result || mysqli_num_rows($product_result) === 0) {

    mysqli_stmt_close($product_stmt);

    echo "Product Not Found.";
    exit();

}


$row = mysqli_fetch_assoc($product_result);

mysqli_stmt_close($product_stmt);


// ======================================================
// UPDATE PRODUCT
// ======================================================

if (isset($_POST['update_product'])) {

    $product_name = trim($_POST['product_name'] ?? '');
    $product_price = $_POST['product_price'] ?? '';
    $product_description = trim($_POST['product_description'] ?? '');
    $product_category = trim($_POST['product_category'] ?? '');
    $product_status = $_POST['product_status'] ?? '';


    // --------------------------------------------------
    // BASIC VALIDATION
    // --------------------------------------------------

    if (
        $product_name === '' ||
        $product_price === '' ||
        $product_description === '' ||
        $product_category === '' ||
        $product_status === ''
    ) {

        echo "<script>
                alert('Please fill in all fields.');
              </script>";

    } elseif (!is_numeric($product_price) || $product_price < 0) {

        echo "<script>
                alert('Please enter a valid product price.');
              </script>";

    } elseif (
        $product_status !== 'Available' &&
        $product_status !== 'Out of Stock'
    ) {

        echo "<script>
                alert('Invalid product status.');
              </script>";

    } else {


        // --------------------------------------------------
        // HANDLE PRODUCT IMAGE
        // --------------------------------------------------

        // Keep current image by default
        $product_image = $row['image'];


        // Check if a new image was uploaded
        if (
            isset($_FILES['product_image']) &&
            $_FILES['product_image']['error'] === UPLOAD_ERR_OK
        ) {

            $original_name = $_FILES['product_image']['name'];
            $temp_image = $_FILES['product_image']['tmp_name'];


            // Get extension
            $file_extension = strtolower(
                pathinfo(
                    $original_name,
                    PATHINFO_EXTENSION
                )
            );


            // Allowed image types
            $allowed_extensions = [
                'jpg',
                'jpeg',
                'png',
                'webp'
            ];


            if (!in_array($file_extension, $allowed_extensions)) {

                echo "<script>
                        alert('Invalid image format. Please upload JPG, JPEG, PNG or WEBP.');
                      </script>";

            } else {


                // Generate unique image name
                $product_image =
                    uniqid('product_', true)
                    . '.'
                    . $file_extension;


                // Upload path
                $upload_path =
                    "../uploads/"
                    . $product_image;


                // Move image
                if (
                    !move_uploaded_file(
                        $temp_image,
                        $upload_path
                    )
                ) {

                    echo "<script>
                            alert('Failed to upload product image.');
                          </script>";

                    // Keep old image
                    $product_image = $row['image'];

                }

            }

        }


        // --------------------------------------------------
        // UPDATE PRODUCT IN DATABASE
        // --------------------------------------------------

        $update_stmt = mysqli_prepare(
            $conn,
            "UPDATE products
             SET
                name = ?,
                price = ?,
                description = ?,
                category = ?,
                image = ?,
                status = ?
             WHERE id = ?"
        );


        if ($update_stmt) {

            $product_price = (float) $product_price;


            mysqli_stmt_bind_param(
                $update_stmt,
                "sdssssi",
                $product_name,
                $product_price,
                $product_description,
                $product_category,
                $product_image,
                $product_status,
                $id
            );


            if (mysqli_stmt_execute($update_stmt)) {

                mysqli_stmt_close($update_stmt);

                echo "<script>
                        alert('Product Updated Successfully!');
                        window.location='view-products.php';
                      </script>";

                exit();

            } else {

                mysqli_stmt_close($update_stmt);

                echo "<script>
                        alert('Failed to Update Product!');
                      </script>";

            }

        } else {

            echo "<script>
                    alert('Database error. Please try again.');
                  </script>";

        }

    }

}


// ======================================================
// HEADER + NAVBAR
// ======================================================

include("includes/header.php");
include("includes/navbar.php");

?>


<!-- ======================================================
     EDIT PRODUCT PAGE
====================================================== -->

<div class="container mt-5 mb-5">

    <div class="card shadow border-0">


        <!-- ==================================================
             PAGE HEADER
        ================================================== -->

        <div class="card-header bg-warning text-dark">

            <h3 class="mb-0">

                ✏️ Edit Product

            </h3>

        </div>


        <!-- ==================================================
             FORM BODY
        ================================================== -->

        <div class="card-body p-4">

            <form action="" method="POST" enctype="multipart/form-data">


                <!-- ==================================================
                     PRODUCT NAME
                ================================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Product Name

                    </label>

                    <input type="text" name="product_name" class="form-control"
                        value="<?php echo htmlspecialchars($row['name']); ?>" required>

                </div>


                <!-- ==================================================
                     PRICE
                ================================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Price

                    </label>

                    <input type="number" name="product_price" class="form-control"
                        value="<?php echo htmlspecialchars($row['price']); ?>" min="0" step="0.01" required>

                </div>


                <!-- ==================================================
                     DESCRIPTION
                ================================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Description

                    </label>

                    <textarea name="product_description" class="form-control" rows="4"
                        required><?php echo htmlspecialchars($row['description']); ?></textarea>

                </div>


                <!-- ==================================================
                     CATEGORY
                ================================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Category

                    </label>

                    <select name="product_category" class="form-select" required>

                        <option value="">

                            Select Category

                        </option>


                        <?php

                        $category_query =
                            "SELECT category_name
                             FROM categories
                             ORDER BY category_name ASC";

                        $category_result =
                            mysqli_query(
                                $conn,
                                $category_query
                            );


                        if (
                            $category_result &&
                            mysqli_num_rows($category_result) > 0
                        ) {

                            while (
                                $category =
                                mysqli_fetch_assoc($category_result)
                            ) {

                                ?>

                                <option value="<?php echo htmlspecialchars($category['category_name']); ?>" <?php

                                   if (
                                       $category['category_name']
                                       === $row['category']
                                   ) {

                                       echo "selected";

                                   }

                                   ?>>

                                    <?php
                                    echo htmlspecialchars(
                                        $category['category_name']
                                    );
                                    ?>

                                </option>

                                <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <!-- ==================================================
                     PRODUCT STATUS
                ================================================== -->

                <div class="mb-4">

                    <label class="form-label fw-semibold">

                        Product Status

                    </label>


                    <select name="product_status" class="form-select" required>

                        <option value="Available" <?php

                        if ($row['status'] === 'Available') {

                            echo "selected";

                        }

                        ?>>

                            Available

                        </option>


                        <option value="Out of Stock" <?php

                        if ($row['status'] === 'Out of Stock') {

                            echo "selected";

                        }

                        ?>>

                            Out of Stock

                        </option>

                    </select>


                    <small class="text-muted">

                        Products marked as "Out of Stock"
                        will not appear in the customer shop.

                    </small>

                </div>


                <!-- ==================================================
                     CURRENT IMAGE
                ================================================== -->

                <div class="mb-3">

                    <label class="form-label fw-semibold">

                        Current Image

                    </label>

                    <br>


                    <?php if (!empty($row['image'])) { ?>

                        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" width="150" height="150"
                            class="img-thumbnail" style="object-fit: cover;" alt="Current Product Image">

                    <?php } else { ?>

                        <p class="text-muted">

                            No image available.

                        </p>

                    <?php } ?>

                </div>


                <!-- ==================================================
                     NEW IMAGE
                ================================================== -->

                <div class="mb-4">

                    <label class="form-label fw-semibold">

                        Choose New Image

                    </label>


                    <input type="file" name="product_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">


                    <small class="text-muted">

                        Leave empty to keep the current image.

                    </small>

                </div>


                <!-- ==================================================
                     BUTTONS
                ================================================== -->

                <div class="d-flex gap-2">

                    <button type="submit" name="update_product" class="btn btn-success">

                        💾 Update Product

                    </button>


                    <a href="view-products.php" class="btn btn-secondary">

                        ← Cancel

                    </a>

                </div>


            </form>

        </div>

    </div>

</div>


<!-- ======================================================
     FOOTER
====================================================== -->

<?php include("../includes/footer.php"); ?>


</body>

</html>