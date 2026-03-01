<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $role       = $_POST['role'];

    if (!$first_name || !$last_name || !$email || !$password || !$role) {

        $error = "All fields are required !";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email format !";
    } else {

        $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {

            $error = "User already registered !";
        } else {
            $profile_pic = '';
            if (!empty($_FILES['profile_pic']['name'])) {
                $profile_pic = $_FILES['profile_pic']['name'];
                $target = "assets/img/" . basename($profile_pic);
                if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    $error = "Profile picture upload failed!";
                }
            }

            if (!$error) {

                $password_hashed = password_hash($password, PASSWORD_DEFAULT);

                $sql = "INSERT INTO users (first_name, last_name, email, password, role, profile_pic)
                        VALUES ('$first_name', '$last_name', '$email', '$password_hashed', '$role', '$profile_pic')";

                if (mysqli_query($conn, $sql)) {
                    $success = "user added successfully! ";
                } else {
                    $error = "user Registration failed: " . mysqli_error($conn);
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
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid min-vh-100 bg-warning d-flex flex-column ">
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
                        background-image: url('assets/img/use.jpg');
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

                    <h2 class="text-center mb-4 text-success fs-3 text-decoration-underline">ADD USER</h2>

                    <form action="register.php" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="first name" class="form-label">first name</label>
                            <input type="text" class="form-control" id="first name" name="first_name" placeholder="Enter first name" required>
                        </div>
                        <div class="mb-3">
                            <label for="last name" class="form-label">last name</label>
                            <input type="text" class="form-control" id="last name" name="last_name" placeholder="Enter last name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="mb-3">
                            <label for="profile_pic" class="form-label">Profile Picture</label>
                            <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                        </div>
                        <div class="mb-3">
                            <label for="role">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="#">select role</option>
                                <option value="admin">admin</option>
                                <option value="farmer">farmer</option>
                            </select>
                        </div>



                        <button type="submit" class="btn btn-success w-100 mb-3">Add User</button>

                    </form>
                     <a href="dashboard.php" class="w-100 btn btn-success text-white text-center mt-4 ">&larr;  Go back to dashboard </a>
                </div>
            </div>




        

</body>

</html>