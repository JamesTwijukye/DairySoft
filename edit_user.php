<?php
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("User ID not provided");
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id LIMIT 1");
if (!$result || mysqli_num_rows($result) !== 1) {
    die("User not found");
}

$user = mysqli_fetch_assoc($result);

$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $role       = $_POST['role'];
    $password_input = $_POST['password'];

    if (!$first_name || !$last_name || !$email || !$role) {
        $error = "All fields except password are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        $profile_pic = $user['profile_pic'];
        if (!empty($_FILES['profile_pic']['name'])) {
            $new_pic = basename($_FILES['profile_pic']['name']);
            $target = "assets/img/" . $new_pic;

            if (!empty($user['profile_pic']) && file_exists("assets/img/" . $user['profile_pic'])) {
                unlink("assets/img/" . $user['profile_pic']);
            }

            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                $error = "Profile picture upload failed!";
            } else {
                $profile_pic = $new_pic;
            }
        }

      
            $password_hashed = password_hash($password_input, PASSWORD_DEFAULT);
        }

        if (
            $first_name == $user['first_name'] &&
            $last_name  == $user['last_name'] &&
            $email      == $user['email'] &&
            $role       == $user['role'] &&
            empty($_FILES['profile_pic']['name']) &&
            empty($password_input)
        ) {
            $error = "No changes were made!";
        } else {
            $sql = "UPDATE users SET
                        first_name  = '$first_name',
                        last_name   = '$last_name',
                        email       = '$email',
                        role        = '$role',
                        profile_pic = '$profile_pic'
                        $update_password
                    WHERE id = {$user['id']}";

            if (mysqli_query($conn, $sql)) {
                $success = "User updated successfully!";
                $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id LIMIT 1");
                $user = mysqli_fetch_assoc($result);
            } else {
                $error = "Update failed: " . mysqli_error($conn);
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid min-vh-100 bg-warning d-flex flex-column">
        <div>
            <?php if ($success): ?>
                <div class="alert alert-success text-center text-white bg-success mb-0"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center text-white bg-danger mb-0"><?= $error ?></div>
            <?php endif; ?>
        </div>

        <div class="min-vh-100 d-flex justify-content-center align-items-center"
            style="background-image: url('assets/img/edit.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="card shadow p-4 w-75" style="max-width: 400px; background: rgba(255,255,255,0.9); z-index: 2;">
                <h2 class="text-center mb-4 text-success fs-3 text-decoration-underline">EDIT USER</h2>

                <form action="edit_user.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" name="first_name" placeholder="Enter first name" required>
                    </div>

                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" name="last_name" placeholder="Enter last name" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" value="<?= htmlspecialchars($user['email']) ?>" name="email" placeholder="Enter email" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password <small>(leave blank to keep current)</small></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                    </div>

                    <div class="mb-3">
                        <label for="profile_pic" class="form-label">Profile Picture</label>
                        <?php if (!empty($user['profile_pic'])): ?>
                            <img src="assets/img/<?= $user['profile_pic'] ?>" width="100" class="mb-2"><br>
                        <?php endif; ?>
                        <input type="file" class="form-control" id="profile_pic" name="profile_pic">
                    </div>

                    <div class="mb-3">
                        <label for="role">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="#">Select role</option>
                            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="farmer" <?= ($user['role'] == 'farmer') ? 'selected' : '' ?>>Farmer</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-100 mb-3">Edit User</button>
                </form>
                 <a href="dashboard.php" class=" btn btn-success text-white text-center mt-4 fs-5">&larr;  Go back to dashboard </a>
            </div>
        </div>
    </div>
</body>

</html>