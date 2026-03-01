<?php
include 'config.php';
?>
<div class="table-responsive mb-5 d-flex flex-column justify-content-center align-items-center">
    <div class="w-25 d-flex justify-content-center align-items-center bg-success mb-4">
        <h3 class="text-center text-white fs-1 mb-3">DAIRY FARM DUTIES</h3>

    </div>
        <a href="add_user.php" class=" w-25 btn  btn-success mb-3 d-flex justify-content-center align-self-start fw-medium">Add Dairy farm duty</a>

    <table class="table table-bordered table-striped ">
        <thead class="table-success">
            <th>Dairy_farm_duty_id</th>
            <th>employee_id</th>
            <th>duty_name</th>
            <th>duty_description</th>
            <th>duty_date</th>
            <th>created_at</th>
            <th>Actions</th>

        </thead>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM  dairyfarm_duties");
        ?>

        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['employee_id'] ?></td>
                    <td><?= $row['duty_name'] ?></td>
                    <td><?= $row['duty_description'] ?></td>
                    <td><?= $row['duty_date'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td class="d-flex justify-content-around">
                        <a href="edit_duty.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-success">
                            <i class="bi bi-pencil"></i>Edit
                        </a>

                        <a href="delete_duty.php?id=<?= $row['id'] ?>"
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