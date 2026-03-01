<?php
session_start();
include 'config.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $name = trim($_POST['name']);
    $quantity  = trim($_POST['quantity']);
    $price      = trim($_POST['price']);
    $created_at   = $_POST['created_at'];

    if (!$name || !$quantity || !$price || !$created_at) {

        $error = "All fields are required!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM products WHERE name='$name' LIMIT 1");

        if (mysqli_num_rows($check) > 0) {
            $error = "Product already registered!";
        } else {

            if (!$error) {

                $sql = "INSERT INTO products (name, quantity, price, created_at)
        VALUES ('$name', '$quantity', '$price', '$created_at')";
                if (mysqli_query($conn, $sql)) {
                    $success = "Product added successfully! ";
                } else {
                    $error = "Product addition failed: " . mysqli_error($conn);
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid min-vh-100 bg-warning d-flex flex-column">
        <div class="">
            <?php if ($success): ?>
                <div class="alert alert-success text-center text-white  bg-success mb-0">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center text-white  bg-success mb-0">
                    <?= $error ?>
                </div>
            <?php endif; ?>
        </div>



        <div class=" min-vh-100 d-flex justify-content-center align-items-center"
            style="
                        background-image: url('assets/img/dpp6.jpg');
                        background-size: cover;
                        background-position: center;
                        background-repeat: no-repeat;
                    ">

            <div class="card shadow p-4 w-75"
                style="
                    max-width: 400px;
                    background: rgba(255,255,255,0.9);
                    z-index: 2;
                ">

                <h2 class="text-center mb-4 text-success fs-3">ADD PRODUCT</h2>

                <form action="add_product.php" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="name" class="form-label">name</label>
                        <input type="text" class="form-control" id=" name" name="name" placeholder="Enter product" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">price</label>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" required>
                    </div>

                    <div class="mb-3">
                        <label for="created_at" class="form-label">created_at</label>
                        <input type="date" class="form-control" id="created_at" name="created_at" placeholder="Enter created_at" required>
                    </div>




                    <button type="submit" class="btn btn-success w-100 mb-3">Add Product</button>

                </form>
            </div>
        </div>






</body>

</html>