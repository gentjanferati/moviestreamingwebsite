<?php 
include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_REQUEST['tmdbID']) && isset($_REQUEST['season'])){
    $tmdb_id = $_REQUEST['tmdbID'];
    $season = $_REQUEST['season'];

    $json = file_get_contents("https://api.themoviedb.org/3/tv/".$tmdb_id."/season/".$season."?api_key=29b41875fd9cc24c70edbf57405c2458");
    $data = json_decode($json);
    $poster = $data->poster_path;
    $sql = "INSERT IGNORE INTO season (tmdbID,season,poster)
                            VALUES('$tmdb_id','$season','$poster')";
                            
    if($conn->query($sql)){
        echo "Success";
        header('Location: list_seasons.php?tmdbID='.$tmdb_id);
        exit();
    } else { 
        echo "Error";
        header('Location: list_seasons.php?tmdbID='.$tmdb_id);
        exit();
    }
} 
echo '2';

?>