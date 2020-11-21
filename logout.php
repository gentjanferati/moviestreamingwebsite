<?php 
session_start();
unset($_SESSION['_user_id']);
header("Location: http://localhost/web/successful_logout.php"); 
exit();
?>