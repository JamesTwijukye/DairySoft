<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header('Location: view.php');
    exit;
}

$id = intval($_GET['id']);

$res = mysqli_query($conn, "SELECT image FROM farmers WHERE id=$id");
$row = mysqli_fetch_assoc($res);
if ($row && $row['image'] && file_exists($row['image'])) {
    unlink($row['image']);
}

mysqli_query($conn, "DELETE FROM farmers WHERE id=$id");

header('Location: farmers.php');
exit;
?>
