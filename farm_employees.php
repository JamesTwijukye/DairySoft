<?php
include 'config.php';
?>

<div class="table-responsive">
    <h3 class="text-center text-success ">DAIRY FARM EMPLOYEES</h3>
    <a href="add_employee.php" class="btn btn-success my-4 w-25">Add Employee</a>
    <table class="table table-bordered table-striped-columns table-success">
        <thead class="table-success">
            <tr class="text-success text-center text-capitalize">
                <th>id</th>
                <th>full name</th>
                <th>job tittle</th>
                <th>phone</th>
                <th>email</th>
                <th>Profile Picture</th>

                <th>hire date</th>
                <th>created at</th>
                <th>actions</th>
            </tr>
        </thead>

        <?php
        $result = mysqli_query($conn, "SELECT * FROM employees");
        ?>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['full_name'] ?></td>
                    <td><?= $row['job_title'] ?></td>
                    <td><?= $row['phone'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td class="text-center">
                        <?php if (!empty($row['profile_pic'])): ?>
                            <img
                                src="assets/img/<?= htmlspecialchars($row['profile_pic']) ?>"
                                alt="Profile Pic"
                                width="70"
                                height="50"
                               >
                        <?php else: ?>
                            <span class="text-muted">No Image</span>
                        <?php endif; ?>
                    </td>

                    <td><?= $row['hire_date'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td class="d-flex justify-content-around">
                        <a href="edit_employee.php?id=<?= $row['id'] ?>" class="btn btn-success"><i class="bi bi-pencil"></i> Edit</a>
                        <a href="delete_employee.php?id=<?= $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this employee')"><i class="bi bi-trash"></i> Delete</a>
                    </td>

                </tr>
        </tbody>
    <?php } ?>
    </table>
</div>