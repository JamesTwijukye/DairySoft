<?php 
include 'config.php';
 ?>
<div class="table-responsive mb-5">
    <h3 class="text-center text-success fs-1 mb-3">USERS</h3>
    <a href="add_user.php"  class="btn btn-success w-25 mb-3">Add User</a>
    <table class="table table-bordered table-striped ">
        <thead class="table-success">
            <th>id</th>
            <th>firstname</th>
            <th>lastname</th>
            <th>email</th>
            <th>password</th>
            <th>role</th>
            <th>created_at</th>
            <th>Actions</th>

        </thead>
        <?php
$result = mysqli_query($conn, "SELECT * FROM users");
?>

<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['first_name'] ?></td>
        <td><?= $row['last_name'] ?></td>
        <td><?= $row['email'] ?></td>
        <td><?= $row['password'] ?></td>
        <td><?= $row['role'] ?></td>
        <td><?= $row['created_at'] ?></td>
        <td class="d-flex justify-content-around">
            <a href="edit_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">
                <i class="bi bi-pencil"></i>Edit
            </a>

            <a href="delete_user.php?id=<?= $row['id'] ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this user?')">
                <i class="bi bi-trash"></i> Delete
            </a>
        </td>
    </tr>
<?php } ?>
</tbody>

    </table>

</div>