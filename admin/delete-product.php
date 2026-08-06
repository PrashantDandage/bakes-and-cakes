<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("../database/db.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $delete_query = "DELETE FROM products WHERE id='$id'";

    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {

        echo "<script>alert('Product Deleted Successfully!');</script>";
        echo "<script>window.location='view-products.php';</script>";

    } else {

        echo "<script>alert('Failed to Delete Product!');</script>";
    }

} else {

    echo "No Product ID Found.";

}
?>