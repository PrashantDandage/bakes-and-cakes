<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("../database/db.php");

if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $delete_query = "DELETE FROM categories WHERE id='$id'";

    $delete_result = mysqli_query($conn, $delete_query);

    if ($delete_result) {

        echo "<script>alert('Category Deleted Successfully!');</script>";
        echo "<script>window.location='view-categories.php';</script>";

    } else {

        echo "<script>alert('Failed to Delete Category!');</script>";

    }

} else {

    echo "No Category ID Found.";

}
?>