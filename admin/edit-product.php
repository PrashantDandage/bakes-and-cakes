<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");

// Check Product ID
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $query = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $query);

    $row = mysqli_fetch_assoc($result);

} else {

    echo "No Product ID Found.";
    exit();

}
?>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-warning text-dark">
            <h3>Edit Product</h3>
        </div>

        <div class="card-body">

            <form action="" method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label>Product Name</label>
                    <input type="text" name="product_name" class="form-control" value="<?php echo $row['name']; ?>">
                </div>

                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" name="product_price" class="form-control" value="<?php echo $row['price']; ?>">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="product_description" class="form-control"
                        rows="4"><?php echo $row['description']; ?></textarea>
                </div>

                <div class="mb-3">

                    <label>Category</label>

                    <select name="product_category" class="form-control">

                        <?php

                        $category_query = "SELECT * FROM categories";
                        $category_result = mysqli_query($conn, $category_query);

                        while ($category = mysqli_fetch_assoc($category_result)) {

                            ?>

                            <option value="<?php echo $category['category_name']; ?>" <?php
                               if ($category['category_name'] == $row['category']) {
                                   echo "selected";
                               }
                               ?>>

                                <?php echo $category['category_name']; ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <div class="mb-3">

                    <label>Current Image</label><br>

                    <img src="../uploads/<?php echo $row['image']; ?>" width="150" class="img-thumbnail">

                </div>

                <div class="mb-3">

                    <label>Choose New Image</label>

                    <input type="file" name="product_image" class="form-control">

                </div>

                <button type="submit" name="update_product" class="btn btn-success">

                    Update Product

                </button>

            </form>

        </div>

    </div>

</div>

<?php

if (isset($_POST['update_product'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_category = $_POST['product_category'];

    $product_image = $_FILES['product_image']['name'];
    $temp_image = $_FILES['product_image']['tmp_name'];

    if ($product_image == "") {

        $product_image = $row['image'];

    } else {

        move_uploaded_file($temp_image, "../uploads/" . $product_image);

    }

    $update_query = "UPDATE products SET
                        name='$product_name',
                        price='$product_price',
                        description='$product_description',
                        category='$product_category',
                        image='$product_image'
                     WHERE id='$id'";

    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {

        echo "<script>alert('Product Updated Successfully!');</script>";
        echo "<script>window.location='view-products.php';</script>";

    } else {

        echo "<script>alert('Failed to Update Product!');</script>";

    }

}

?>

</body>

</html>