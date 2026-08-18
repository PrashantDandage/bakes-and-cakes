<?php

include("../database/db.php");


// ======================================================
// CUSTOMER REGISTRATION
// ======================================================

if (isset($_POST['register'])) {

    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';


    // ==================================================
    // VALIDATION
    // ==================================================

    if (
        $name === '' ||
        $email === '' ||
        $phone === '' ||
        $address === '' ||
        $password === '' ||
        $confirm_password === ''
    ) {

        echo "<script>
                alert('Please fill in all fields.');
              </script>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Please enter a valid email address.');
              </script>";

    } elseif ($password !== $confirm_password) {

        echo "<script>
                alert('Passwords do not match!');
              </script>";

    } elseif (strlen($password) < 6) {

        echo "<script>
                alert('Password must contain at least 6 characters.');
              </script>";

    } else {


        // ==================================================
        // CHECK WHETHER EMAIL ALREADY EXISTS
        // ==================================================

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM customers WHERE email = ? LIMIT 1"
        );


        if ($check_stmt) {

            mysqli_stmt_bind_param(
                $check_stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($check_stmt);

            $check_result = mysqli_stmt_get_result($check_stmt);


            if (mysqli_num_rows($check_result) > 0) {

                echo "<script>
                        alert('Email already registered!');
                      </script>";

                mysqli_stmt_close($check_stmt);

            } else {

                mysqli_stmt_close($check_stmt);


                // ==================================================
                // HASH PASSWORD
                // ==================================================

                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );


                // ==================================================
                // INSERT CUSTOMER
                // ==================================================

                $insert_stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO customers
                    (name, email, phone, address, password)
                    VALUES (?, ?, ?, ?, ?)"
                );


                if ($insert_stmt) {

                    mysqli_stmt_bind_param(
                        $insert_stmt,
                        "sssss",
                        $name,
                        $email,
                        $phone,
                        $address,
                        $hashed_password
                    );


                    if (mysqli_stmt_execute($insert_stmt)) {

                        mysqli_stmt_close($insert_stmt);

                        echo "<script>
                                alert('Registration Successful!');
                                window.location='login.php';
                              </script>";

                        exit();

                    } else {

                        mysqli_stmt_close($insert_stmt);

                        echo "<script>
                                alert('Registration failed. Please try again.');
                              </script>";
                    }

                } else {

                    echo "<script>
                            alert('Something went wrong. Please try again.');
                          </script>";
                }
            }

        } else {

            echo "<script>
                    alert('Something went wrong. Please try again.');
                  </script>";
        }
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Registration - Bakes & Cakes</title>


    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


    <!-- ==================================================
         REGISTRATION SECTION
         ================================================== -->

    <div class="container mt-5 mb-5">

        <div class="row justify-content-center">

            <div class="col-md-6">


                <div class="card shadow border-0 rounded-4">


                    <!-- Header -->

                    <div class="card-header bg-success text-white text-center rounded-top-4 py-3">

                        <h3 class="mb-0">

                            🍰 Customer Registration

                        </h3>

                    </div>


                    <!-- Body -->

                    <div class="card-body p-4">


                        <form action="" method="POST">


                            <!-- Full Name -->

                            <div class="mb-3">

                                <label for="name" class="form-label fw-semibold">

                                    Full Name

                                </label>


                                <input type="text" id="name" name="name" class="form-control"
                                    placeholder="Enter your full name"
                                    value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>

                            </div>


                            <!-- Email -->

                            <div class="mb-3">

                                <label for="email" class="form-label fw-semibold">

                                    Email Address

                                </label>


                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>

                            </div>


                            <!-- Phone -->

                            <div class="mb-3">

                                <label for="phone" class="form-label fw-semibold">

                                    Phone Number

                                </label>


                                <input type="tel" id="phone" name="phone" class="form-control"
                                    placeholder="Enter your phone number"
                                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required>

                            </div>


                            <!-- Address -->

                            <div class="mb-3">

                                <label for="address" class="form-label fw-semibold">

                                    Delivery Address

                                </label>


                                <textarea id="address" name="address" class="form-control" rows="3"
                                    placeholder="Enter your delivery address"
                                    required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>

                            </div>


                            <!-- Password -->

                            <div class="mb-3">

                                <label for="password" class="form-label fw-semibold">

                                    Password

                                </label>


                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Enter your password" minlength="6" required>

                                <small class="text-muted">

                                    Password must contain at least 6 characters.

                                </small>

                            </div>


                            <!-- Confirm Password -->

                            <div class="mb-4">

                                <label for="confirm_password" class="form-label fw-semibold">

                                    Confirm Password

                                </label>


                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control" placeholder="Confirm your password" minlength="6" required>

                            </div>


                            <!-- Register Button -->

                            <div class="d-grid">

                                <button type="submit" name="register" class="btn btn-success btn-lg rounded-pill">

                                    Register

                                </button>

                            </div>


                        </form>

                    </div>


                    <!-- Footer -->

                    <div class="card-footer text-center bg-white border-0 pb-4">

                        <span class="text-muted">

                            Already have an account?

                        </span>


                        <a href="login.php" class="text-success fw-semibold text-decoration-none">

                            Login

                        </a>

                    </div>


                </div>

            </div>

        </div>

    </div>


    <!-- Bootstrap JS -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>