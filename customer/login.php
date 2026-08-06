<?php
session_start();
include("../database/db.php");
?>
<?php

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Check Email
    $query = "SELECT * FROM customers WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        // Verify Password
        if (password_verify($password, $row['password'])) {

            $_SESSION['customer_id'] = $row['id'];
            $_SESSION['customer_name'] = $row['name'];
            $_SESSION['customer_email'] = $row['email'];

            echo "<script>alert('Login Successful!');</script>";
            echo "<script>window.location='home.php';</script>";

        } else {

            echo "<script>alert('Incorrect Password!');</script>";

        }

    } else {

        echo "<script>alert('Email not found!');</script>";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header bg-success text-white text-center">
                        <h3>Customer Login</h3>
                    </div>

                    <div class="card-body">

                        <form method="POST">

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email" name="email" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label>Password</label>

                                <input type="password" name="password" class="form-control" required>

                            </div>

                            <div class="d-grid">

                                <button type="submit" name="login" class="btn btn-success">

                                    Login

                                </button>

                            </div>

                        </form>

                    </div>

                    <div class="card-footer text-center">

                        Don't have an account?

                        <a href="register.php">Register</a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>