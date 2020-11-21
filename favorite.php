<?php include('conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(isset($_POST['action']) && isset($_SESSION['_user_id']) && isset($_REQUEST['tmdbID'])) {
    $id = $_SESSION['_user_id'];
    $tmdbID = $_POST['tmdbID'];
    if($_POST['action'] == 'add') {
        $sql = "INSERT IGNORE INTO wish_list(userID,mediaID) VALUES('$id','$tmdbID')";
    } else {
        $sql = "DELETE FROM wish_list WHERE userID='$id' AND mediaID='$tmdbID'";
    }
    if($conn->query($sql)) {
        echo 'OK';
        exit();
    } else {
        echo 'Error';
        exit();
    }
} 
if(isset($_POST['action']) && $_POST['action']=='delete' && isset($_SESSION['_user_id'])) {
    $id = $_SESSION['_user_id'];
    $sql = "DELETE FROM wish_list WHERE userID='$id'";
    if(isset($_POST['tmdbID'])) {
        $tmdbID = $_POST['tmdbID'];
        $sql .= " AND mediaID '$tmdbID'";
    }
    if($conn->query($sql)){
        echo $sql;
        //echo 'OK';
        exit();
    } else {
        echo 'Error';
        exit();
    }
} else {
    echo 'Missing';
    exit();
}
?>