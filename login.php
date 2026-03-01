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

    if (!$first_name || !$last_name || !$email || !$password) 
        {
        $error = "All fields are required !";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
     {
        $error = "The email format is not correct";
        
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name']  = $user['last_name'];
                $_SESSION['email']      = $user['email'];
                $_SESSION['role']       = $user['role'];

                $success = "Login successful, redirecting...";


                echo "<script>
                        setTimeout(function() {
                            window.location.href = '" . ($user['role'] === 'admin' ? 'dashboard.php' : 'dashboard.php') . "';
                        }, 2000);
                      </script>";
            } else {
                $error = "Login failed - Incorrect password";
            }
        } else {
            $error = "Login failed - Email not found!";
        }
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

        <div class="row flex-grow-1 bg-info">
           
            <div class="col-md-4 d-flex justify-content-center align-items-center position-relative bg-success text-white overflow-hidden">

                <img src="assets/img/cb.jpg" alt="Logo" class="img-fluid" style="max-width:250px; z-index:2;">

              

            </div>


            <div class="col-md-8 d-flex justify-content-center align-items-center"
                style="
                        background-image: url('assets/img/ga.jpg');
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

                    <h2 class="text-center mb-4">Login</h2>

                    <form action="login.php" method="POST" enctype="multipart/form-data">

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

                        
                        <div class="mb-3 d-flex justify-content-md-center align-items-center">
                            <button type="submit" class="bg-success text-center text-white w-75">Login</button>

                        </div>

                        <p class="text-center text-success mb-3">Not registered, register <a href="register.php">here</a> </p>


                    </form>
                </div>
            </div>




        </div>

</body>

</html>