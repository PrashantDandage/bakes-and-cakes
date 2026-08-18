<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">

    <div class="container">

        <!-- Logo -->

        <a class="navbar-brand fw-bold fs-2 text-warning" href="home.php">

            🍰 Bakes & Cakes

        </a>

        <!-- Mobile Toggle -->

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- Menu -->

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="home.php">
                        Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="shop.php">
                        Shop
                    </a>
                </li>

                <?php if (isset($_SESSION['customer_id'])) { ?>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="my-orders.php">
                            My Orders
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="cart.php">
                            Cart
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="profile.php">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>

                    <li class="nav-item ms-3">

                        <span class="fw-bold">

                            👋 <?php echo $_SESSION['customer_name']; ?>

                        </span>

                    </li>

                    <li class="nav-item ms-3">

                        <a href="logout.php" class="btn btn-danger rounded-pill px-4">

                            Logout

                        </a>

                    </li>

                <?php } else { ?>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="about.php">
                            About
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="contact.php">
                            Contact
                        </a>
                    </li>

                    <li class="nav-item ms-3">

                        <a href="login.php" class="btn btn-outline-success rounded-pill px-4">

                            Login

                        </a>

                    </li>

                    <li class="nav-item ms-2">

                        <a href="register.php" class="btn btn-success rounded-pill px-4">

                            Register

                        </a>

                    </li>

                <?php } ?>

            </ul>

        </div>

    </div>

</nav>