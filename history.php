<?php include('conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(isset($_POST['action']) && $_POST['action']=='delete' && isset($_SESSION['_user_id'])) {
    
    $id = $_SESSION['_user_id'];
    $sql = "DELETE FROM watch_list WHERE watch_list.userID='$id'";
    if(isset($_POST['tmdbID'])) {
        $tmdbID = $_POST['tmdbID'];
        $sql .= " AND watch_list.mediaID='$tmdbID'";
    }
    if($conn->query($sql)){
        echo $sql;
        //echo 'OK';
        exit();
    } else {
        echo $sql;
        //echo 'Error';
        exit();
    }
}
if(isset($_SESSION['_user_id']) && isset($_POST['tmdbID'])) {
    $id = $_SESSION['_user_id'];
    $tmdbID = $_POST['tmdbID'];

    $sql = "INSERT IGNORE INTO watch_list(userID,mediaID) VALUES('$id','$tmdbID')";
    
    if($conn->query($sql)) {
        echo 'OK';
        exit();
    } else {
        echo 'Error';
        exit();
    }
}
?>