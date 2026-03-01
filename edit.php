<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit;
}

$id = intval($_GET['id']);
$res = mysqli_query($conn, "SELECT * FROM farmers WHERE id=$id");
$farmer = mysqli_fetch_assoc($res);

if (!$farmer) {
    echo "Farmer not found";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob     = mysqli_real_escape_string($conn, $_POST['dob']);
    $image   = $farmer['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $image = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image);
    }

    $sql = "UPDATE farmers SET 
                name='$name', 
                email='$email', 
                phone='$phone', 
                address='$address', 
                dob='$dob', 
                image='$image' 
            WHERE id=$id";

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
    <title>Edit Farmer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f1f3f6; }
        .form-container { width: 450px; margin: 60px auto; padding: 20px 25px; background-color: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; font-size: 22px; margin-bottom: 20px; color: #0d6efd; }
        .btn-primary { width: 100%; }
        input.form-control { height: 38px; font-size: 14px; }
        .mb-3 { margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Edit Farmer</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <input type="text" name="name" class="form-control" value="<?= $farmer['name'] ?>" required>
        </div>
        <div class="mb-3">
            <input type="email" name="email" class="form-control" value="<?= $farmer['email'] ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="phone" class="form-control" value="<?= $farmer['phone'] ?>" required>
        </div>
        <div class="mb-3">
            <input type="text" name="address" class="form-control" value="<?= $farmer['address'] ?>" required>
        </div>
        <div class="mb-3">
            <input type="date" name="dob" class="form-control" value="<?= $farmer['dob'] ?>" required>
        </div>
        <div class="mb-3">
            <input type="file" name="image" class="form-control">
            <?php if ($farmer['image']): ?>
                <img src="<?= $farmer['image'] ?>" class="thumb mt-2">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Update Farmer</button>
        <a href="view.php" class="btn btn-secondary mt-2">Back to View</a>
    </form>
</div>

</body>
</html>
