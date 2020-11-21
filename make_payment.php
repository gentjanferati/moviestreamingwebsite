<?php include('conn.php');
//block direct access
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
if(!IS_AJAX) {die('Restricted access');}

session_start();
if(isset($_POST['date']) && isset($_SESSION['_user_id'])) {
    if($_POST['date'] == 'null') {
        $date = new DateTime('now');
        $date->modify("+1 month 2 hours"); //2 hours changetime
        $date = $date->format('Y-m-d');
    } else {
        $date = new DateTime($_POST['date']);
        $date->modify('+1 month');
        $date = $date->format('Y-m-d'); 
    }
    $sql = "UPDATE user SET status='Subscribed', expirydate='$date' WHERE id='".$_SESSION['_user_id']."'";
    if($conn->query($sql)) {
        echo 'Successfully';
    } else {
        echo 'Error: '.$conn->error;
    }
}
?>