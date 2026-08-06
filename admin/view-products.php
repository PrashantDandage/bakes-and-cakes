<?php
session_start();

if (!isset($_SESSION['admin_email'])) {
    header("Location: login.php");
    exit();
}

include("includes/header.php");
include("includes/navbar.php");
include("../database/db.php");
?>

<div class="container mt-5">

    <div class="card">

        <div class="card-header">
            <h3>View Products</h3>
        </div>

        <div class="card-body">

            <?php

            $query = "SELECT * FROM products";

            $result = mysqli_query($conn, $query);
            ?>
            <table class="table table-bordered table-hover">

                <thead class="table-dark text-center">

                    <tr>

                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody class="text-center">

                    <?php

                    while ($row = mysqli_fetch_assoc($result)) {

                        ?>

                        <tr>

                            <td><?php echo $row['id']; ?></td>

                            <td>
                                <img src="../uploads/<?php echo $row['image']; ?>" width="80" height="80">
                            </td>

                            <td><?php echo $row['name']; ?></td>

                            <td><?php echo $row['category']; ?></td>

                            <td>₹<?php echo $row['price']; ?></td>

                            <td><?php echo $row['status']; ?></td>

                            <td>

                                <a href="edit-product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="delete-product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this product?');">
                                    Delete
                                </a>

                            </td>

                        </tr>

                        <?php

                    }

                    ?>

                </tbody>

            </table>


        </div>

    </div>

</div>

</body>

</html>