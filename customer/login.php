<?php

session_start();

include("../database/db.php");


// ======================================================
// CUSTOMER LOGIN
// ======================================================

if (isset($_POST['login'])) {

    // Get and sanitize input
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    // --------------------------------------------------
    // Basic validation
    // --------------------------------------------------

    if ($email === '' || $password === '') {

        echo "<script>
                alert('Please enter email and password.');
              </script>";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Please enter a valid email address.');
              </script>";

    } else {

        // --------------------------------------------------
        // Prepared Statement
        // --------------------------------------------------

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, email, password
             FROM customers
             WHERE email = ?
             LIMIT 1"
        );


        if ($stmt) {

            mysqli_stmt_bind_param($stmt, "s", $email);

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);


            // --------------------------------------------------
            // Check Customer
            // --------------------------------------------------

            if ($row = mysqli_fetch_assoc($result)) {

                // --------------------------------------------------
                // Verify Password
                // --------------------------------------------------

                if (password_verify($password, $row['password'])) {

                    // Prevent session fixation
                    session_regenerate_id(true);


                    // Store customer information in session
                    $_SESSION['customer_id'] = $row['id'];
                    $_SESSION['customer_name'] = $row['name'];
                    $_SESSION['customer_email'] = $row['email'];


                    // Close statement
                    mysqli_stmt_close($stmt);


                    // Redirect to customer home
                    header("Location: home.php");
                    exit();

                } else {

                    echo "<script>
                            alert('Invalid email or password.');
                          </script>";
                }

            } else {

                echo "<script>
                        alert('Invalid email or password.');
                      </script>";
            }


            mysqli_stmt_close($stmt);

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

    <title>Customer Login - Bakes & Cakes</title>


    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>


<body class="bg-light">


    <!-- ==================================================
         LOGIN SECTION
         ================================================== -->

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">


                <div class="card shadow border-0 rounded-4">


                    <!-- Header -->

                    <div class="card-header bg-success text-white text-center rounded-top-4 py-3">

                        <h3 class="mb-0">

                            🍰 Customer Login

                        </h3>

                    </div>


                    <!-- Body -->

                    <div class="card-body p-4">


                        <form method="POST" action="">


                            <!-- Email -->

                            <div class="mb-3">

                                <label for="email" class="form-label fw-semibold">

                                    Email Address

                                </label>


                                <input type="email" id="email" name="email" class="form-control"
                                    placeholder="Enter your email"
                                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>

                            </div>


                            <!-- Password -->

                            <div class="mb-3">

                                <label for="password" class="form-label fw-semibold">

                                    Password

                                </label>


                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="Enter your password" required>

                            </div>


                            <!-- Login Button -->

                            <div class="d-grid">

                                <button type="submit" name="login" class="btn btn-success btn-lg rounded-pill">

                                    Login

                                </button>

                            </div>


                        </form>

                    </div>


                    <!-- Footer -->

                    <div class="card-footer text-center bg-white border-0 pb-4">

                        <span class="text-muted">

                            Don't have an account?

                        </span>


                        <a href="register.php" class="text-success fw-semibold text-decoration-none">

                            Register

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