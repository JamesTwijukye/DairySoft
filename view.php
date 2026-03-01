<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Farmers</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f1f3f6; }
        .table-container { max-width: 900px; margin: 50px auto; }
        img.thumb { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }

        /* Make buttons in one line */
        .actions { display: flex; gap: 5px; flex-wrap: nowrap; }
        .action-btn i { margin-right: 4px; }
    </style>
</head>
<body>

<div class="table-container">
    <h2 class="text-center text-primary mb-4">Farmers List</h2>
    <a href="add.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle"></i> Add New Farmer</a>

    <table class="table table-striped table-bordered shadow-sm">
        <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>DOB</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM farmers ORDER BY id DESC");
            if ($res) {
                while ($row = mysqli_fetch_assoc($res)) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['dob']}</td>
                        <td><img src='{$row['image']}' class='thumb'></td>
                        <td>
                            <div class='actions'>
                                <a href='view_single.php?id={$row['id']}' class='btn btn-sm btn-info action-btn' title='View'>
                                    <i class='bi bi-eye'></i> View
                                </a>
                                <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-primary action-btn' title='Edit'>
                                    <i class='bi bi-pencil-square'></i> Edit
                                </a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-sm btn-danger action-btn' title='Delete' onclick=\"return confirm('Are you sure?')\">
                                    <i class='bi bi-trash'></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
