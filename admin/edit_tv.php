<?php include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_POST['action']) && isset($_POST['tmdbID']) && isset($_POST['type'] )&& $_POST['action'] == 'delete') {
  $tmdbID = $_POST['tmdbID'];
  if($conn->query("DELETE FROM media WHERE tmdbID = '$tmdbID'")) {
    if($_POST['type'] == '1') { //Remove Seasons And Episodes
      if($conn->query("DELETE FROM season WHERE tmdbID = '$tmdbID'")) {
        if($conn->query("DELETE FROM episode WHERE tmdbID = '$tmdbID'")) {
        } else {
          echo 'Error Deleting Episodes';
          exit();
        }
      } else {
        echo 'Error Deleting Seasons';
        exit();
      }
    }
    if($conn->query("DELETE FROM wish_list WHERE mediaID = '$tmdbID'")){
      if($conn->query("DELETE FROM watch_list WHERE mediaID = '$tmdbID'")) {
        echo 'Removed Successfully';
        exit();
      } else {
        echo 'Error Deleting TV Show Show From Watchlist';
        exit();
      }
    } else {
      echo 'Error Deleting TV Show From Wishlist';
      exit();
    }
  } else {
    echo 'Error Deleting Tv Show';
    exit();
  }
}

if(isset($_POST['submit'])) {
  $tmdbID = isset($_POST['tmdb']) ? $_POST['tmdb'] : '';
  $title = isset($_POST['title']) ? replaceAccents($_POST['title']) : '';
  $description = isset($_POST['description']) ? replaceAccents($_POST['description']) : '';
  $poster = isset($_POST['poster']) ? $_POST['poster'] : '';
  $background = isset($_POST['background']) ? $_POST['background'] : '';
  $trailer = isset($_POST['trailer']) ? $_POST['trailer'] : '';
  $rating = isset($_POST['rating']) ? $_POST['rating'] : '';
  $duration = isset($_POST['duration']) ? $_POST['duration'] : '';
  $genre = isset($_POST['genre']) ? $_POST['genre'] : '';
  $year = isset($_POST['year']) ? $_POST['year'] : '';
  $release = isset($_POST['release']) ? $_POST['release'] : '';

  $genre = implode(",",$genre);

  $sql = "UPDATE media SET  title='$title',
                            description='$description',
                            poster='$poster',
                            background='$background',
                            trailer='$trailer',
                            imdbRating='$rating',
                            duration='$duration',
                            genre='$genre',
                            year='$year',
                            releaseDate='$release'
          WHERE tmdbID = '$tmdbID'";
        
  if($conn->query($sql)) {
    $_SESSION['_success_msg'] = "<div class='success'>Changes Were Saved With Success</div>";
    header('Location: edit_tv.php?tmdbID='.$tmdbID);
    exit();
  } else {
    $_SESSION['_success_msg'] = "<div class='failed'>There Has Been An Error</div>";
    header('Location: edit_tv.php?tmdbID='.$tmdbID);
    exit();
  }
}
if(isset($_GET['tmdbID'])) {
  $tmdbID = $_GET['tmdbID'];
  $sql = "SELECT * FROM media WHERE tmdbID='$tmdbID'";
  $res = $conn->query($sql);
  if($res->num_rows !== 1) { 
    echo 'TV Not Found';
    header('Location: list_tv.php');
    exit();
  }

  $title = '';
  $description = '';
  $poster = '';
  $background = '';
  $trailer = '';
  $imdbRating = '';
  $duration = '';
  $genre = '';
  $year = '';
  $releaseDate = '';

  $row = $res->fetch_array(MYSQLI_ASSOC);
  $title = $row['title'];
  $description = $row['description'];
  $poster = $row['poster'];
  $background = $row['background'];
  $trailer = $row['trailer'];
  $imdbRating = $row['imdbRating'];
  $duration = $row['duration'];
  $genre = $row['genre'];
  $year = $row['year'];
  $releaseDate = $row['releaseDate'];

  $genre = explode(',', $genre);
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
            <input type="text" name="title" value="<?=$title;?>">
            <br>
            <label>Description</label>
            <textarea rows="8" id="description" name="description" cols="20"><?=$description;?></textarea>
            <br>
            <label>Poster</label>
            <input type="text" name="poster" value="<?=$poster;?>">
            <br>
            <label>Background</label>
            <input type="text" name="background" value="<?=$background;?>">
            <br>
            <label>Trailer ID</label>
            <input type="text" name="trailer" value="<?=$trailer;?>">
            <br>
            <label>IMDB Rating</label>
            <input type="number" name="rating" value="<?=$imdbRating;?>">
            <br>

            <input type="submit" name="submit" value="Save">
        </div>
        <div class="right-col">
          <label>Duration</label>
            <input type="number" name="duration" value="<?=$duration;?>">
            <br>
            <label>Year</label>
            <input type="number" name="year" value="<?=$year;?>">
            <br>
            <label>Release Date</label>
            <input type="date" name="release" value="<?=$releaseDate;?>">
            <br>
            <label>Genres</label>
            <div id="buton1">Choose genres</div>
            <div id="dropdown1">
              <?php $res = $conn->query("SELECT * FROM genre ORDER BY name LIMIT 27");
                while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                  $id = $row['id'];
                  $name = $row['name']; 
                  $bool = in_array($id, $genre) ? true : false;
                  ?>
                  <div class="genre-item <?php if($bool) echo 'selected';?>">
                    <input type="checkbox" name="genre[]" value="<?= $id;?>" <?php if($bool) echo 'checked';?>/><?= $name?><br>
                  </div>
              <?php } ?>
            </div>
            <br>
            <label>Fetch Latest Data</label>
            <input type="hidden" name="tmdb" id="tmdb" value="<?= $tmdbID;?>">
            <button class="fetchBtn" type="button">Fetch</button>
            <br>

            <label>Seasons List</label>
            <button class="goToList" type="button"><a href="list_seasons.php?tmdbID=<?=$tmdbID;?>">Seasons</a></button>
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
    let id=$('#tmdb').val();
    $.ajax({
      method: 'GET',
      url: 'https://api.themoviedb.org/3/tv/'+id+'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=videos,credits,images',
      success: function(data) {
        $('input[name="title"]').val(data['name']);
        $('#description').val(data['overview']);
        $('input[name="poster"]').val(data['poster_path']);
        $('input[name="background"]').val(data['images']['backdrops'][0]['file_path']);
        $('input[name="trailer"]').val(data['videos']['results'][0]['key']);
        $('input[name="duration"]').val(data['episode_run_time'][0]);
        $('input[name="release"]').val(data['first_air_date']);
        $('input[name="year"]').val(data['first_air_date'].substring(0,4));

        for(let i=0; i< data['genres'].length; i++){
          $('input[value="'+data['genres'][i]['id']+'"]').prop("checked", true);
          $('input[value="'+data['genres'][i]['id']+'"]').parent('.genre-item').addClass('selected');
        }
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
  echo 'TV Not Found';
}?>