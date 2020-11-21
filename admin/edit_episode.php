<?php include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_POST['action']) && isset($_POST['tmdbID']) && isset($_POST['season']) && isset($_POST['episode']) && $_POST['action'] == 'delete') {
  $tmdbID = $_POST['tmdbID'];
  $season = $_POST['season'];
  $episode = $_POST['episode'];
  if($conn->query("DELETE FROM episode WHERE tmdbID = '$tmdbID' AND season='$season' AND episode='$episode'")) {
    echo 'Removed Successfully';
    exit();
  } else {
    echo 'Error Deleting Seasons';
    exit();
  }
}

if(isset($_POST['submit'])) {
  $tmdbID = isset($_POST['tmdb']) ? $_POST['tmdb'] : '';
  $season = isset($_POST['season']) ? $_POST['season'] : '';
  $episode = isset($_POST['episode']) ? $_POST['episode'] : '';
  $title = isset($_POST['title']) ? replaceAccents($_POST['title']) : '';
  $description = isset($_POST['description']) ? replaceAccents($_POST['description']) : '';
  $url = isset($_POST['url']) ? $_POST['url'] : '';
  $airdate = isset($_POST['airdate']) ? $_POST['airdate'] : '';

  $sql = "UPDATE episode SET title='$title',
                            description='$description',
                            url='$url',
                            airdate='$airdate'
          WHERE tmdbID = '$tmdbID' AND season = '$season' AND episode = '$episode'";
  if($conn->query($sql)) {
    $_SESSION['_success_msg'] = "<div class='success'>Changes Were Saved With Success</div>";
    header('Location: edit_episode.php?tmdbID='.$tmdbID.'&season='.$season.'&episode='.$episode);
    exit();
  } else {
    $_SESSION['_success_msg'] = "<div class='failed'>There Has Been An Error</div>";
    header('Location: edit_episode.php?tmdbID='.$tmdbID.'&season='.$season.'&episode='.$episode);
    exit();
  }
}

if(isset($_GET['tmdbID']) && isset($_GET['season']) && isset($_GET['episode'])) {
  $tmdbID= $_GET['tmdbID'];
  $season= $_GET['season'];
  $episode= $_GET['episode'];

  $sql = "SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season' AND episode='$episode'";
  $res = $conn->query($sql);
  if($res->num_rows !== 1) { 
    echo 'Episode Not Found';
    header('Location: list_episodes.php?tmdbID'.$tmdbID.'&season='.$season);
    exit();
  }

  $title = '';
  $description = '';
  $url = '';
  $airdate = '';

  $row = $res->fetch_array(MYSQLI_ASSOC);
  $title = $row['title'];
  $description = $row['description'];
  $url = $row['url'];
  $airdate = $row['airdate'];

  $tvshow = '';
  $res = $conn->query("SELECT title FROM media WHERE tmdbID='$tmdbID'");
  $row = $res->fetch_array(MYSQLI_ASSOC);
  $tvshow = $row['title'];
?>
<!DOCTYPE html>
<html>
<head>
 <title>Admin</title>
 <link rel="stylesheet" type="text/css" href="stylesheet.css">
 <script src="../js/jquery-3.5.0.js"></script>
</head>
<body>
<div class="header">
  <ul class="menu">
    <li class="item"><a href="index.php">Dashboard</a></li>
    <li class="item"><a href="#">Movies</a>
  <ul>
    <li class="dropdown"><a href="list_movies.php">Movie List</a></li>
    <li class="dropdown"><a href="discover.php">New Movie</a></li>
    </ul>
  </li>
  <li class="item"><a href="#">TV Series</a>
    <ul>
    <li class="dropdown"><a href="list_tv.php">TV List</a></li>
    <li class="dropdown"><a href="discover.php">New TV</a></li>
    </ul>
  </li>
  <li class="item"><a href="list_users.php">Users</a>
  <li class="item"><a href="settings.php">Settings</a></li>
  <li class="item"><a href="logout.php">Logout</a></li>
  </li>
  </ul>
</div>

<div class="content-single">
<?php
    if(isset($_SESSION['_success_msg'])) {      
      echo $_SESSION['_success_msg'];
      unset($_SESSION['_success_msg']);
    }?>
  <form method="POST">
        <div class="left-col">
            <label>Title</label>
            <input type="text" value="<?=$tvshow;?>" readonly>
            <br>
            <label>Season</label>
            <input type="text" name="season" value="<?=$season;?>" readonly>
            <br>
            <label>Episode</label>
            <input type="text" name="episode" value="<?=$episode;?>" readonly>
            <br>
            <label>Description</label>
            <textarea name="description" id="description" rows="8" cols="20"><?=$description;?></textarea>
            <br>
            <input type="submit" name="submit" value="Save">
          </div>
          <div class="right-col">
            <label>Episode Title</label>
            <input type="text" name="title" value="<?=$title;?>">
            <br>
            <label>URL</label>
            <input type="text" name="url" value="<?=$url;?>">
            <br>
            <label>Airdate</label>
            <input type="date" name="airdate" value="<?=$airdate;?>">
            <br>
            <label>Fetch Latest Data</label>
            <input type="hidden" name="tmdb" id="tmdb" value="<?= $tmdbID;?>">
            <button class="fetchBtn" type="button">Fetch</button>
            <br>
        </div>
  </form>
</div>
<script>
$(document).ready(function(){
  $('#buton1').on('click', function(){
    $('#dropdown1').toggle();
  });
  $('.genre-item').on('click', function() {
    $(this).toggleClass('selected');
    var checkbox = $(this).find('input');
    checkbox.prop("checked", !checkbox.prop("checked"));
  });
  $('.fetchBtn').on('click', function() {
    let tmdb=$('#tmdb').val();
    let season=$('input[name="season"]').val();
    let episode=$('input[name="episode"]').val();
    $.ajax({
      method: 'GET',
      url: 'https://api.themoviedb.org/3/tv/'+tmdb+'/season/'+season+'/episode/'+episode+'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=trailers,credits,images',
      success: function(data) {
        $('input[name="title"]').val(data['name']);
        $('#description').val(data['overview']);
        $('input[name="airdate"]').val(data['air_date']);
      },
      error: function(xhr) {
        console.log(url);
        alert('Error: '+xhr.status+' '+xhr.statusText);
      }
    });
  });
});
</script>
</body>
</html>
<?php } else {
  echo 'Episode Not Found';
} ?>