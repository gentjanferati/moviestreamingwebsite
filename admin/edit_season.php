<?php include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_POST['action']) && isset($_POST['tmdbID']) && isset($_POST['season']) && isset($_POST['type'] )&& $_POST['action'] == 'delete') {
  $tmdbID = $_POST['tmdbID'];
  $season = $_POST['season'];
  if($conn->query("DELETE FROM season WHERE tmdbID = '$tmdbID' AND season='$season'")) {
    if($_POST['type'] == '1') { //Remove Episodes
      if($conn->query("DELETE FROM episode WHERE tmdbID = '$tmdbID' AND season='$season'")) {
      } else {
        echo 'Error Deleting Episodes';
        exit();
      }
    }
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
  
  $poster = isset($_POST['poster']) ? $_POST['poster'] : '';

  $sql = "UPDATE episode SET poster='$poster'
          WHERE tmdbID = '$tmdbID' AND season = '$season'";

  if($conn->query($sql)) {
    $_SESSION['_success_msg'] = "<div class='success'>Changes Were Saved With Success</div>";
    header('Location: edit_season.php?tmdbID='.$tmdbID.'&season='.$season);
    exit();
  } else {
    $_SESSION['_success_msg'] = "<div class='failed'>There Has Been An Error</div>";
    header('Location: edit_season.php?tmdbID='.$tmdbID.'&season='.$season);
    exit();
  }
}

if(isset($_GET['tmdbID']) && isset($_GET['season'])) {
  $tmdbID= $_GET['tmdbID'];
  $season= $_GET['season'];

  $sql = "SELECT * FROM season WHERE tmdbID='$tmdbID' AND season='$season'";
  $res = $conn->query($sql);

  if($res->num_rows !== 1) { 
    echo 'Season Not Found';
    header('Location: list_seasons.php?tmdbID'.$tmdbID);
    exit();
  }

  $poster = '';

  $row = $res->fetch_array(MYSQLI_ASSOC);
  $poster = $row['poster'];

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
        <div class="left-col">
            <label>Title</label>
            <input type="text" value="<?=$tvshow;?>" readonly>
            <br>
            <label>Season</label>
            <input type="text" name="season" value="<?=$season;?>" readonly>
            <br>
            <label>Poster</label>
            <input type="text" name="poster" value="<?=$poster;?>">
            <br>
            <label>Fetch Latest Data</label>
            <input type="hidden" name="tmdb" id="tmdb" value="<?= $tmdbID;?>">
            <button class="fetchBtn" type="button">Fetch</button>
            <br>
            <label>Episodes List</label>
            <button class="goToList" type="button"><a href="list_episodes.php?tmdbID=<?=$tmdbID;?>&season=<?=$season;?>">Episodes</a></button>
            <br>
            <input type="submit" name="submit" value="Save">
          </div>
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
    $.ajax({
      method: 'GET',
      url: 'https://api.themoviedb.org/3/tv/'+tmdb+'/season/'+season+'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=trailers,credits,images',
      success: function(data) {
        $('input[name="poster"]').val(data['poster_path']);
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
  echo 'Season Not Found';
} ?>