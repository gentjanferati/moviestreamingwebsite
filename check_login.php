<?php
include('conn.php');
if(isset($_REQUEST['uid'])) {
    $uid = $_REQUEST['uid'];
    $sql = "SELECT * FROM user WHERE id = '".$uid."'";
    $res = mysqli_query($conn, $sql);
    if(mysqli_num_rows($res) == 1) {
        session_start();
        $_SESSION['_user_id'] = $uid;
        echo '200';
    } else {
        echo 'Error 404';
    }
} else {
    echo 'Error 4042';
}
?>