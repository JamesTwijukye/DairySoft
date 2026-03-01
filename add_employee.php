<?php 
session_start();
include 'config.php';

error_reporting(E_ALL);
ini_set('display_errors',1);

$success ='';
$error = '';

if($_SERVER["REQUEST_METHOD"]=='POST'){
$full_name = $_POST['full_name'];
$job_title = $_POST['job_title'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$profile_pic = $_FILES['profile_pic'];
$hire_date = $_POST['hire_date'];

if (
    empty($full_name) ||
    empty($job_title) ||
    empty($phone) ||
    empty($email) ||
    empty($hire_date)
) {
    $error = "All fields are required!";
}

elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){

$error = "invalid email format!";

}
else{

 $check = mysqli_query($conn, "SELECT id FROM employees WHERE email='$email' LIMIT 1");

        if (mysqli_num_rows($check) === 1) {
            $error = "employee already registered !";
        } else {
            $profile_pic = '';
            if (!empty($_FILES['profile_pic']['name'])) {
                $profile_pic = $_FILES['profile_pic']['name'];
                $target = "assets/img/" . basename($profile_pic);
                if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target)) {
                    $error = "Profile picture upload failed!";
                }
            }
        }

        if (!$error) {

               $sql = "INSERT INTO employees 
        (full_name, job_title, phone, email, profile_pic, hire_date)
        VALUES 
        ('$full_name', '$job_title', '$phone', '$email', '$profile_pic', '$hire_date')";


                if (mysqli_query($conn, $sql)) {
                    $success = "employee added successfully! ";
                } else {
                    $error = "employee Registration failed: " . mysqli_error($conn);
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
        <div class="w-100">
            <?php if ($success): ?>
                <div class="alert alert-success text-center text-white  bg-success mb-0">
                    <?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger text-center text-white  bg-danger mb-0">
                    <?= $error ?>
                </div>
            <?php endif; ?>
        </div>

       

            <div class=" min-vh-100 d-flex justify-content-center align-items-center"
                style="
                        background-image: url('assets/img/ade.jpg');
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

                    <h2 class="text-center mb-4 text-success fs-3 text-decoration-underline">ADD DAIRY EMPLOYEE</h2>

                    <form action="add_employee.php" method="POST" enctype="multipart/form-data" >

                        <div class="mb-3">
                            <label for="full name" class="form-label  text-success">full name</label>
                            <input type="text" class="form-control rounded-5" id="full name" name="full_name" placeholder="Enter full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="job tittle" class="form-label text-success">job tittle</label>
                            <input type="text" class="form-control rounded-5" id="job title" name="job_title" placeholder="Enter job tittle" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-success">Email address</label>
                            <input type="email" class="form-control rounded-5" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label text-success">phone</label>
                            <input type="number" class="form-control rounded-5" id="phone" name="phone" placeholder="Enter phone" required>
                        </div>
                       

                      
                        <div class="mb-3">
                            <label for="profile_pic" class="form-label text-success">Profile Picture</label>
                            <input type="file" class="form-control rounded-5" id="profile_pic" name="profile_pic">
                        </div>
                        <div class="mb-3">
                            <label for="hire_date" class="form-label  text-success">hire_date</label>
                            <input type="date" class="form-control rounded-5" id="hire_date" name="hire_date" placeholder="Enter hire date" required>
                        </div>


                        <button type="submit" class="btn btn-success w-100 mb-3 rounded-5">Add employee</button>

                    </form>
                     <a href="dashboard.php" class="w-100 btn btn-success text-white text-center mt-4 rounded-5">&larr;  Go back to dashboard </a>
                </div>
            </div>




        

</body>

</html>