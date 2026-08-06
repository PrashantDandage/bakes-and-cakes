<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $query = "SELECT * FROM categories WHERE id='$id'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

} else {

    echo "No Category ID Found.";
    exit();

}
?>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-warning text-dark">
            <h3>Edit Category</h3>
        </div>

        <div class="card-body">

            <form action="" method="POST">

                <div class="mb-3">

                    <label>Category Name</label>

                    <input type="text" name="category_name" class="form-control"
                        value="<?php echo $row['category_name']; ?>">

                </div>

                <button type="submit" name="update_category" class="btn btn-success">
                    Update Category
                </button>

            </form>
            <?php

            if (isset($_POST['update_category'])) {

                $category_name = $_POST['category_name'];

                $update_query = "UPDATE categories
                     SET category_name='$category_name'
                     WHERE id='$id'";

                $update_result = mysqli_query($conn, $update_query);

                if ($update_result) {

                    echo "<script>alert('Category Updated Successfully!');</script>";
                    echo "<script>window.location='view-categories.php';</script>";

                } else {

                    echo "<script>alert('Failed to Update Category!');</script>";

                }

            }

            ?>

            </body>

            </html>

        </div>

    </div>

</div>