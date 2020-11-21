<?php
include('conn.php');
if(!isset($_SESSION)) { session_start(); } 
if(!isset($_SESSION['_user_id'])) { 
    header('Location: http://localhost/web/login/');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Successful Login</title>
<link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />

<script
  src="https://code.jquery.com/jquery-3.5.1.js"
  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <a href="http://localhost/web/home/" class="logo"><img src="http://localhost/web/img/logo.png"></a>   
    </header>
    <div class="sidebar">
        <div class="toggle-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
    <ul>
        <li><a href="http://localhost/web/home/">Home</a></li>
        <li><a href="http://localhost/web/movies/">Movies</a></li>
        <li><a href="http://localhost/web/tvshows/">TV Shows</a></li>
        <li><a href="#" id="genre">Genres</a></li>
        <li><a href="http://localhost/web/search/">Search</a></li>
        <?php if(!isset($_SESSION)) { session_start(); } 
            if(isset($_SESSION['_user_id'])) {?>
        <li><a href="http://localhost/web/profile/">Profile</a></li>
        <li><a href="http://localhost/web/logout/">Logout</a></li>
            <?php } else { ?>
                <li><a href="http://localhost/web/login/">Login</a></li>
            <?php } ?>
        </ul>
    </div>
<div class="content">
    <?php $res = $conn->query("SELECT genre.name, genre.slug FROM genre INNER JOIN media ON media.genre LIKE CONCAT('%', genre.id, '%') GROUP BY genre.name ORDER BY genre.name ASC")?>
            <div class="box-genres">
                <?php while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                    $name = $row['name'];
                    $slug = $row['slug'];?>
                    <div class="genre-box"><h3><a href="http://localhost/web/genre/<?=$slug?>/"><?=$name?></a></h3></div>
                <?php } ?>
                <div class="close-btn"><img src="http://localhost/web/img/close.png"></div>
            </div>
    <div class="banner">
        <div class="supay">
            <div class="sulogo"><img src="http://localhost/web/img/logo.png" witdth="250px" height="80px"></div>
            <div>
                <h1>Successfully Logged In</h1><br>
                <h1>Have Fun Watching</h1><br>
                <a href="http://localhost/web/home/">Continue To Homepage</a>
            </div>
        </div>
    </div>
    <ul class="sci">
        <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
        <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
        <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
    </ul>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.toggle-btn').on('click', function(){
            $('.sidebar').toggleClass('active');
        });
        $('#genre').click(function() {
            $('.box-genres').addClass('active');
            $('.sidebar').removeClass('active');
        });
        $('.close-btn').click(function(){
            $('.box-genres').removeClass('active');
        });
    });
</script>
</body>
</html>