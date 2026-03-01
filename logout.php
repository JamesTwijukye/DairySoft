<?php
session_start();

session_unset();

session_destroy();

header("Cache-control:no-store,no-cache,must-revalidate,max-age=0");
header("Pragma:no-cache");

header("Location: login.php");
exit;

?>