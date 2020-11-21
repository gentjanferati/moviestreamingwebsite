<?php 
include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_REQUEST['tmdbID']) && isset($_REQUEST['season']) && isset($_REQUEST['episode'])){
    $tmdbid = $_REQUEST['tmdbID'];
    $season = $_REQUEST['season'];
    $episode = $_REQUEST['episode'];
    $json = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdbid.'/season/'.$season.'/episode/'.$episode.'?api_key=29b41875fd9cc24c70edbf57405c2458');
    $data = json_decode($json);
    $title = replaceAccents($data->name);
    $disc = replaceAccents($data->overview);
    $airdate = $data->air_date;
    
    $json2 = file_get_contents('https://api.themoviedb.org/3/tv/'.$tmdbid.'?api_key=29b41875fd9cc24c70edbf57405c2458');
    $data2 = json_decode($json2);
    $slug = slugify($data2->name).'-'.$season.'-'.$episode;
    $sql = "INSERT IGNORE INTO episode (tmdbID,episode,season,title,description,airdate,url,slug)
                                    VALUES('$tmdbid','$episode','$season','$title','$disc','$airdate',NULL,'$slug')";
    if($conn->query($sql)){
        echo "Success";
        header('Location: list_episodes.php?tmdbID='.$tmdbid.'&season='.$season);
        exit();
    } else {
        echo "Error ".$conn->error;
        header('Location: list_episodes.php?tmdbID='.$tmdbid.'&season='.$season);
        exit();
    } 
        
}
?>