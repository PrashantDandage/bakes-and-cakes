<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "bakes_and_cakes";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

// echo "Database Connected Successfully";

?>