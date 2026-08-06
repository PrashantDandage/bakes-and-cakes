<?php
session_start();
include("../database/db.php");
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admin WHERE email='$email' AND password='$password'";

    $result = mysqli_query($conn, $query);

    $count = mysqli_num_rows($result);

    if ($count == 1) {
        $_SESSION['admin_email'] = $email;

        header("Location: dashboard.php");
        exit();
    } else {
        echo "Invalid Email or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container">

        <div class="row justify-content-center mt-5">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header text-center">
                        <h3>Admin Login</h3>
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

                            <button type="submit" name="login" class="btn btn-primary w-100">
                                Login
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>