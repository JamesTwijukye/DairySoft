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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_delete'])) {

        $sql = "DELETE FROM users WHERE id=$id LIMIT 1";
        if (mysqli_query($conn, $sql)) {
            $success = "User deleted successfully!";
        } else {
            $error = "User deletion failed!";
        }
    }

    if (isset($_POST['cancel'])) {
        header("Location: dashboard.php");
        exit;
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <div class="container-fluid min-vh-100 bg-warning d-flex flex-column justify-content-center align-items-center">
        <div>
            <?php if ($success): ?>
                <div class="alert alert-success text-center text-white bg-success mb-0"><?= $success ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center text-white bg-danger mb-0"><?= $error ?></div>
            <?php endif; ?>
        </div>
        <div class="w-50 bg-danger" style="height:50vh;">
            <div class="w-100 bg-primary d-flex justify-content-center align-items-center" style="height:50%;">

                <p>You are about to Deleting <?= htmlspecialchars($user['first_name']  . '  ' .  $user['last_name']) ?> </p>
            </div>
            <div class="w-100 bg-success d-flex flex-column justify-content-center align-items-center " style="height:50%;">
                <form method="POST" action="delete_user.php?id=<?= $id ?>" class="w-100 bg-light">
                    <div class="w-100 bg-success d-flex flex-row justify-content-center align-items-center gap-lg-5 " style="height:100%;">
                        <button type="submit" name="cancel" class="btn btn-secondary w-25">
                            Cancel
                        </button>

                        <button type="submit" name="confirm_delete" class="btn btn-danger w-25">
                            Delete
                        </button>
                    </div>
                </form>
                <a href="dashboard.php" class="w-25 btn btn-primary text-white text-center mt-4">Go back to dashboard &rarr;</a>


            </div>

        </div>
    </div>

</body>

</html>