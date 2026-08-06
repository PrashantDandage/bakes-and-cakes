<?php
session_start();

if (!isset($_SESSION['customer_email'])) {

    header("Location: login.php");
    exit();

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Customer Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="card shadow">

            <div class="card-header bg-success text-white">

                <h3>Customer Dashboard</h3>

            </div>

            <div class="card-body">

                <h4>Welcome,
                    <?php echo $_SESSION['customer_name']; ?>
                </h4>

                <p>
                    Email:
                    <?php echo $_SESSION['customer_email']; ?>
                </p>

                <a href="logout.php" class="btn btn-danger">
                    Logout
                </a>

            </div>

        </div>

    </div>

</body>

</html>