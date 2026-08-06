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

        <div class="card-header bg-primary text-white">
            <h3>Add Category</h3>
        </div>

        <div class="card-body">

            <form action="" method="POST">

                <div class="mb-3">

                    <label>Category Name</label>

                    <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name"
                        required>

                </div>

                <button type="submit" name="add_category" class="btn btn-success">
                    Add Category
                </button>

            </form>
            <?php

            if (isset($_POST['add_category'])) {

                $category_name = $_POST['category_name'];

                $query = "INSERT INTO categories (category_name)
              VALUES ('$category_name')";

                $result = mysqli_query($conn, $query);

                if ($result) {

                    echo "<script>alert('Category Added Successfully!');</script>";
                    echo "<script>window.location='view-categories.php';</script>";

                } else {

                    echo "<script>alert('Failed to Add Category!');</script>";

                }

            }

            ?>

            </body>

            </html>

        </div>

    </div>

</div>