<?php

session_start();
session_destroy();
session_start();

$_SESSION['message'] = "Logged Out";
$_SESSION['status'] = 200;
$_SESSION['success'] = true;
$_SESSION['next'] = '/php';

header('Location: ../response.php');
exit(); 

?>
