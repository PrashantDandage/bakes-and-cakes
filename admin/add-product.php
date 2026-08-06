<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");
?>

<div class="container mt-5">

    <div class="card">

        <div class="card-header">
            <h3>Add Product</h3>
        </div>

        <div class="card-body">

            <form action="" method="POST" enctype="multipart/form-data">

                <!-- Product Name -->
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="product_name" class="form-control" required>
                </div>

                <!-- Product Price -->
                <div class="mb-3">
                    <label class="form-label">Product Price</label>
                    <input type="number" step="0.01" name="product_price" class="form-control" required>
                </div>

                <!-- Product Description -->
                <div class="mb-3">
                    <label class="form-label">Product Description</label>
                    <textarea name="product_description" class="form-control" rows="4" required></textarea>
                </div>

                <!-- Product Image -->
                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <input type="file" name="product_image" class="form-control" required>
                </div>

                <!-- Product Category -->
                <div class="mb-3">

                    <label>Category</label>

                    <select name="product_category" class="form-control">

                        <option value="">-- Select Category --</option>

                        <?php

                        $category_query = "SELECT * FROM categories";
                        $category_result = mysqli_query($conn, $category_query);

                        while ($category = mysqli_fetch_assoc($category_result)) {

                            ?>

                            <option value="<?php echo $category['category_name']; ?>">

                                <?php echo $category['category_name']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- Product Status -->
                <div class="mb-3">
                    <label class="form-label">Status</label>

                    <select name="product_status" class="form-select">

                        <option value="Available">Available</option>
                        <option value="Out of Stock">Out of Stock</option>

                    </select>
                </div>

                <button type="submit" name="add_product" class="btn btn-primary">
                    Add Product
                </button>

            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['add_product'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_category = $_POST['product_category'];
    $product_status = $_POST['product_status'];

    // Image information
    $product_image = $_FILES['product_image']['name'];
    $product_image = $_FILES['product_image']['name'];
    $temp_image = $_FILES['product_image']['tmp_name'];

    move_uploaded_file($temp_image, "../uploads/" . $product_image);
    if (isset($_POST['add_product'])) {

        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_description = $_POST['product_description'];
        $product_category = $_POST['product_category'];
        $product_status = $_POST['product_status'];

        $product_image = $_FILES['product_image']['name'];
        $temp_image = $_FILES['product_image']['tmp_name'];

        move_uploaded_file($temp_image, "../uploads/" . $product_image);

        $query = "INSERT INTO products
       (name, description, price, image, category, status)
        VALUES
    ('$product_name',
     '$product_description',
     '$product_price',
     '$product_image',
     '$product_category',
     '$product_status')";

        $result = mysqli_query($conn, $query);

        if ($result) {

            echo "<script>alert('Product Added Successfully!');</script>";

        } else {

            echo "<script>alert('Failed to Add Product!');</script>";

        }

    }


}

?>

</body>

</html>