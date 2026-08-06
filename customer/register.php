<?php
include("../database/db.php");
?>
<?php

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check Password Match
    if ($password != $confirm_password) {

        echo "<script>alert('Passwords do not match!');</script>";

    } else {

        // Check Email Already Exists
        $check_email = "SELECT * FROM customers WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_email);

        if (mysqli_num_rows($check_result) > 0) {

            echo "<script>alert('Email already registered!');</script>";

        } else {

            // Encrypt Password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert Customer
            $query = "INSERT INTO customers
            (name,email,phone,address,password)
            VALUES
            ('$name','$email','$phone','$address','$hashed_password')";

            $result = mysqli_query($conn, $query);

            if ($result) {

                echo "<script>alert('Registration Successful!');</script>";
                echo "<script>window.location='login.php';</script>";

            } else {

                echo "<script>alert('Registration Failed!');</script>";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Registration</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white text-center">

                        <h3>Customer Registration</h3>

                    </div>

                    <div class="card-body">

                        <form action="" method="POST">

                            <div class="mb-3">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Phone Number</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" name="register" class="btn btn-primary">
                                    Register
                                </button>
                            </div>

                        </form>

                    </div>

                    <div class="card-footer text-center">

                        Already have an account?

                        <a href="login.php">Login</a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>