<?php
include('../conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(isset($_SESSION['_admin_username'])) { ?>
<!DOCTYPE html>
<html>
<head>
 <title>Admin</title>
 <link rel="stylesheet" type="text/css" href="stylesheet.css">
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
<div class="content1">
  <div class="dash">
    <div class="dashitem">
        <?php   $res1=$conn->query("SELECT * FROM media WHERE tv=0");
                $res2=$conn->query("SELECT * FROM media WHERE tv=1");
                $res3=$conn->query("SELECT * FROM season");
                $res4=$conn->query("SELECT * FROM episode");
                $res5=$conn->query("SELECT * FROM user");

        
        ?>
      <a href="#"><img src="img/movie.jpg" alt="Movies" style="width: 300px;height:450px;"></a>
      <div class="dashstat"><h1 onclick="window.location.assign('list_movies.php')"><?=$res1->num_rows?> Movies</h1></div>
    </div>
    <div class="dashitem">
      <a href="#"><img src="img/tvshow.jpg" alt="TV Shows" style="width: 300px;height:450px;"></a>
      <div class="dashstat"><h1 onclick="window.location.assign('list_tv.php')"><?=$res2->num_rows?> TvShows</h1></div>
    </div>
    <div class="dashitem">
      <a href="#"><img src="img/season.jpg" alt="Seasons" style="width: 300px;height:450px;"></a>
      <div class="dashstat"><h1><?=$res3->num_rows?> Seasons</h1></div>
    </div>
    <div class="dashitem">
      <a href="#"><img src="img/episode.jpg" alt="Episodes" style="width: 300px;height:450px;"></a>
      <div class="dashstat"><h1><?=$res4->num_rows?> Episodes</h1></div>
    </div>
    <div class="dashitem">
      <a href="#"><img src="img/users.jpg" alt="Users" style="width: 300px;height:450px;"></a>
      <div class="dashstat"><h1 onclick="window.location.assign('list_users.php')"><?=$res5->num_rows?> Users</h1></div>
    </div>
  </div>
</div>
</body>
</html>
<?php
} else {
    //If user is not logged in include login page
    require_once('login.php');
}?>