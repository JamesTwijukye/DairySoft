<?php 
include 'config.php';
 ?>
<div class="table-responsive mb-5">
    <h3 class="text-center text-success fs-1 mb-3">PRODUCTS</h3>
    <a href="add_product.php"  class="btn btn-success w-25 mb-3">Add Product</a>
    <table class="table table-bordered table-striped ">
        <thead class="table-success">
            <th>id</th>
            <th>name</th>
            <th>price</th>
            <th>quantity</th>
            
            <th>created_at</th>
            <th>Actions</th>

        </thead>
        <?php
$result = mysqli_query($conn, "SELECT * FROM products");
?>

<tbody>
<?php while ($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['price'] ?></td>
        <td><?= $row['quantity'] ?></td>
        <td><?= $row['created_at'] ?></td>
       
        <td>
            <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil"></i>Edit
            </a>

            <a href="delete_product.php?id=<?= $row['id'] ?>"
               class="btn btn-sm btn-danger"
               onclick="return confirm('Delete this product?')">
                <i class="bi bi-trash"></i> Delete
            </a>
        </td>
    </tr>
<?php } ?>
</tbody>

    </table>

</div>