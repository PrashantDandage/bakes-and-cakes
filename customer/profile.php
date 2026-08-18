<?php

session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/db.php");


// =====================================================
// CUSTOMER LOGIN CHECK
// =====================================================

if (!isset($_SESSION['customer_id'])) {

    header("Location: login.php");
    exit();

}

$customer_id = (int) $_SESSION['customer_id'];


// =====================================================
// FETCH CUSTOMER
// =====================================================

$query = "
    SELECT *
    FROM customers
    WHERE id = '$customer_id'
    LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {

    session_destroy();

    header("Location: login.php");
    exit();

}

$customer = mysqli_fetch_assoc($result);


// =====================================================
// CUSTOMER DATA
// =====================================================

$name = $customer['name'] ?? '';
$email = $customer['email'] ?? '';
$phone = $customer['phone'] ?? '';
$address = $customer['address'] ?? '';


// =====================================================
// UPDATE PROFILE
// =====================================================

$success_message = '';
$error_message = '';

if (isset($_POST['update_profile'])) {

    $new_name = trim($_POST['name'] ?? '');
    $new_phone = trim($_POST['phone'] ?? '');
    $new_address = trim($_POST['address'] ?? '');

    if ($new_name == '') {

        $error_message = "Name cannot be empty.";

    } else {

        $update_query = "
            UPDATE customers
            SET
                name = ?,
                phone = ?,
                address = ?
            WHERE id = ?
        ";

        $stmt = mysqli_prepare($conn, $update_query);

        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $new_name,
                $new_phone,
                $new_address,
                $customer_id
            );

            if (mysqli_stmt_execute($stmt)) {

                // Update session
                $_SESSION['customer_name'] = $new_name;

                $name = $new_name;
                $phone = $new_phone;
                $address = $new_address;

                $success_message = "Profile updated successfully.";

            } else {

                $error_message = "Unable to update your profile.";

            }

            mysqli_stmt_close($stmt);

        } else {

            $error_message = "Database error. Please try again.";

        }

    }

}


// =====================================================
// HEADER + NAVBAR
// =====================================================

include("../includes/header.php");
include("../includes/navbar.php");

?>

<div class="container py-5">


    <!-- =================================================
         BREADCRUMB
    ================================================== -->

    <nav aria-label="breadcrumb" class="mb-4">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">

                <a href="dashboard.php">
                    Dashboard
                </a>

            </li>

            <li class="breadcrumb-item active">

                My Profile

            </li>

        </ol>

    </nav>


    <!-- =================================================
         PAGE HEADING
    ================================================== -->

    <div class="profile-page-heading mb-5">

        <div>

            <h1 class="fw-bold">

                <i class="bi bi-person-circle text-warning"></i>

                My Profile

            </h1>

            <p class="text-muted mb-0">

                View and manage your account information.

            </p>

        </div>

    </div>


    <!-- =================================================
         ALERT MESSAGES
    ================================================== -->

    <?php if ($success_message != '') { ?>

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>

            <?php echo htmlspecialchars($success_message); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>

    <?php } ?>


    <?php if ($error_message != '') { ?>

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle-fill"></i>

            <?php echo htmlspecialchars($error_message); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert">
            </button>

        </div>

    <?php } ?>


    <!-- =================================================
         PROFILE CONTENT
    ================================================== -->

    <div class="row g-4">


        <!-- =================================================
             PROFILE SUMMARY
        ================================================== -->

        <div class="col-lg-4">

            <div class="card profile-summary-card h-100">

                <div class="card-body text-center p-4">


                    <!-- Avatar -->

                    <div class="profile-avatar-large">

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <!-- Name -->

                    <h3 class="fw-bold mt-4">

                        <?php echo htmlspecialchars($name); ?>

                    </h3>


                    <!-- Email -->

                    <p class="text-muted">

                        <i class="bi bi-envelope"></i>

                        <?php echo htmlspecialchars($email); ?>

                    </p>


                    <hr>


                    <!-- Account Details -->

                    <div class="profile-summary-item">

                        <span>

                            <i class="bi bi-person"></i>

                            Account Type

                        </span>

                        <strong>

                            Customer

                        </strong>

                    </div>


                    <div class="profile-summary-item">

                        <span>

                            <i class="bi bi-shield-check"></i>

                            Account Status

                        </span>

                        <span class="badge bg-success">

                            Active

                        </span>

                    </div>


                    <div class="profile-summary-item">

                        <span>

                            <i class="bi bi-envelope-check"></i>

                            Email

                        </span>

                        <strong>

                            Verified

                        </strong>

                    </div>


                </div>

            </div>

        </div>


        <!-- =================================================
             EDIT PROFILE
        ================================================== -->

        <div class="col-lg-8">

            <div class="card profile-edit-card">

                <div class="card-header">

                    <h4 class="fw-bold mb-1">

                        <i class="bi bi-pencil-square"></i>

                        Account Information

                    </h4>

                    <p class="text-muted mb-0">

                        Update your personal information below.

                    </p>

                </div>


                <div class="card-body p-4">


                    <form method="POST">


                        <!-- Name -->

                        <div class="mb-4">

                            <label for="name" class="form-label fw-semibold">

                                Full Name

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-person"></i>

                                </span>

                                <input type="text" id="name" name="name" class="form-control"
                                    value="<?php echo htmlspecialchars($name); ?>" required>

                            </div>

                        </div>


                        <!-- Email -->

                        <div class="mb-4">

                            <label for="email" class="form-label fw-semibold">

                                Email Address

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-envelope"></i>

                                </span>

                                <input type="email" id="email" class="form-control"
                                    value="<?php echo htmlspecialchars($email); ?>" readonly>

                            </div>

                            <small class="text-muted">

                                Email address cannot be changed here.

                            </small>

                        </div>


                        <!-- Phone -->

                        <div class="mb-4">

                            <label for="phone" class="form-label fw-semibold">

                                Phone Number

                            </label>

                            <div class="input-group">

                                <span class="input-group-text">

                                    <i class="bi bi-telephone"></i>

                                </span>

                                <input type="tel" id="phone" name="phone" class="form-control"
                                    value="<?php echo htmlspecialchars($phone); ?>"
                                    placeholder="Enter your phone number">

                            </div>

                        </div>


                        <!-- Address -->

                        <div class="mb-4">

                            <label for="address" class="form-label fw-semibold">

                                Delivery Address

                            </label>

                            <div class="input-group">

                                <span class="input-group-text align-items-start pt-3">

                                    <i class="bi bi-geo-alt"></i>

                                </span>

                                <textarea id="address" name="address" class="form-control" rows="4"
                                    placeholder="Enter your delivery address"><?php echo htmlspecialchars($address); ?></textarea>

                            </div>

                        </div>


                        <!-- Buttons -->

                        <div class="d-flex gap-2 flex-wrap">

                            <button type="submit" name="update_profile" class="btn btn-success px-4">

                                <i class="bi bi-check-lg"></i>

                                Save Changes

                            </button>


                            <a href="dashboard.php" class="btn btn-outline-dark px-4">

                                <i class="bi bi-arrow-left"></i>

                                Back to Dashboard

                            </a>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         ACCOUNT QUICK LINKS
    ================================================== -->

    <div class="row g-4 mt-4">


        <div class="col-md-4">

            <a href="my-orders.php" class="profile-quick-link">

                <div class="profile-quick-icon bg-primary">

                    <i class="bi bi-receipt"></i>

                </div>

                <div>

                    <h5>
                        My Orders
                    </h5>

                    <p>
                        View your order history
                    </p>

                </div>

                <i class="bi bi-arrow-right ms-auto"></i>

            </a>

        </div>


        <div class="col-md-4">

            <a href="cart.php" class="profile-quick-link">

                <div class="profile-quick-icon bg-success">

                    <i class="bi bi-cart"></i>

                </div>

                <div>

                    <h5>
                        Shopping Cart
                    </h5>

                    <p>
                        View your cart items
                    </p>

                </div>

                <i class="bi bi-arrow-right ms-auto"></i>

            </a>

        </div>


        <div class="col-md-4">

            <a href="logout.php" class="profile-quick-link">

                <div class="profile-quick-icon bg-danger">

                    <i class="bi bi-box-arrow-right"></i>

                </div>

                <div>

                    <h5>
                        Logout
                    </h5>

                    <p>
                        Sign out of your account
                    </p>

                </div>

                <i class="bi bi-arrow-right ms-auto"></i>

            </a>

        </div>


    </div>

</div>


<!-- =====================================================
     PROFILE PAGE CSS
====================================================== -->

<style>
    /* =========================================================
   PAGE HEADING
========================================================= */

    .profile-page-heading h1 {
        color: #2c1d12;
    }


    /* =========================================================
   CARDS
========================================================= */

    .profile-summary-card,
    .profile-edit-card {
        border: none;

        border-radius: 20px;

        overflow: hidden;

        box-shadow:
            0 8px 30px rgba(0, 0, 0, 0.08);
    }


    /* =========================================================
   PROFILE SUMMARY
========================================================= */

    .profile-avatar-large {
        width: 120px;
        height: 120px;

        margin: 10px auto 0;

        display: flex;

        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #fff1e6;

        color: #d35400;

        font-size: 58px;
    }

    .profile-summary-item {
        display: flex;

        justify-content: space-between;

        align-items: center;

        gap: 15px;

        padding: 15px 0;

        border-bottom: 1px solid #eeeeee;

        text-align: left;
    }

    .profile-summary-item:last-child {
        border-bottom: none;
    }

    .profile-summary-item span:first-child {
        color: #777;
    }

    .profile-summary-item i {
        margin-right: 6px;
    }


    /* =========================================================
   EDIT PROFILE
========================================================= */

    .profile-edit-card .card-header {
        background: #ffffff;

        padding: 22px 25px;

        border-bottom: 1px solid #eeeeee;
    }

    .profile-edit-card .input-group-text {
        background: #f8f9fa;

        min-width: 48px;

        justify-content: center;
    }

    .profile-edit-card .form-control,
    .profile-edit-card .input-group-text {
        border-color: #dee2e6;
    }

    .profile-edit-card .form-control:focus {
        border-color: #198754;

        box-shadow:
            0 0 0 0.2rem rgba(25, 135, 84, 0.12);
    }


    /* =========================================================
   QUICK LINKS
========================================================= */

    .profile-quick-link {
        display: flex;

        align-items: center;

        gap: 15px;

        padding: 18px;

        background: #ffffff;

        border-radius: 18px;

        text-decoration: none;

        color: #222;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, 0.07);

        transition: all 0.3s ease;
    }

    .profile-quick-link:hover {
        color: #222;

        transform: translateY(-4px);

        box-shadow:
            0 14px 30px rgba(0, 0, 0, 0.12);
    }

    .profile-quick-link h5 {
        margin: 0 0 4px;

        font-weight: 700;
    }

    .profile-quick-link p {
        margin: 0;

        color: #777;

        font-size: 14px;
    }

    .profile-quick-icon {
        min-width: 50px;

        width: 50px;

        height: 50px;

        display: flex;

        align-items: center;

        justify-content: center;

        border-radius: 14px;

        color: #ffffff;

        font-size: 21px;
    }


    /* =========================================================
   MOBILE
========================================================= */

    @media (max-width: 768px) {

        .profile-page-heading h1 {
            font-size: 30px;
        }

        .profile-summary-item {
            flex-direction: column;

            align-items: flex-start;

            gap: 5px;
        }

    }
</style>


<?php

include("../includes/footer.php");

?>