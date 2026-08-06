<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("../database/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = (int) $_POST['order_id'];

    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query = "UPDATE orders
              SET status='$status'
              WHERE id='$order_id'";

    mysqli_query($conn, $query);

}

header("Location: order-details.php?id=" . $order_id);
exit();
?>