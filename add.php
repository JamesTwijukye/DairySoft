<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob     = mysqli_real_escape_string($conn, $_POST['dob']);
    $image   = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $image = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $sql = "INSERT INTO farmers (name, email, phone, address, dob, image, created_at) 
            VALUES ('$name', '$email', '$phone', '$address', '$dob', '$image', NOW())";

    if (mysqli_query($conn, $sql)) {
        header('Location: view.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Farmer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f6;
        }
        .form-container {
            width: 450px; /* narrower width */
            margin: 60px auto;
            padding: 20px 25px; /* compact padding */
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
        .btn-primary {
            width: 100%;
        }
        input.form-control {
            height: 38px; /* shorter input height */
            font-size: 14px;
        }
        .mb-3 {
            margin-bottom: 10px; /* reduced spacing */
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add Farmer</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Name" required>
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required>
        </div>

        <div class="mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Phone" required>
        </div>

        <div class="mb-3">
            <input type="text" name="address" class="form-control" placeholder="Address" required>
        </div>

        <div class="mb-3">
            <input type="date" name="dob" class="form-control" placeholder="Date of Birth" required>
        </div>

        <div class="mb-3">
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add Farmer</button>
        <a href="view.php" class="btn btn-secondary mt-2">Back to View</a>
    </form>
</div>

</body>
</html>
