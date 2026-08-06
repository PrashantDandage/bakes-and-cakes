<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");

$query = "SELECT * FROM categories";
$result = mysqli_query($conn, $query);
?>

<div class="container mt-5">

    <div class="card">

        <div class="card-header bg-primary text-white">
            <h3>View Categories</h3>
        </div>

        <div class="card-body">

            <table class="table table-bordered table-hover">

                <thead class="table-dark text-center">

                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody class="text-center">

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td><?php echo $row['id']; ?></td>

                            <td><?php echo $row['category_name']; ?></td>

                            <td><?php echo $row['created_at']; ?></td>

                            <td>

                                <a href="edit-category.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="delete-category.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this category?');">
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>