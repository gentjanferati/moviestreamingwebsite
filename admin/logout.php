<?php 
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
$_SESSION["_admin_username"] = "";
$_SESSION["_admin_role"] = "";
session_destroy();
header("Location: index.php");
?>