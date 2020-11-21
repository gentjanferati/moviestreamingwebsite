<?php 
if(!isset($_SESSION)) { session_start(); } 
include('conn.php');
include('some_functions.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$url = 'http://localhost/web/movies/';

if(isset($_GET['order_by'])) {
    if($_GET['order_by'] == 'release') {
        $order = ' ORDER BY releaseDate DESC';
        $url.= 'order/release/';
    } elseif($_GET['order_by'] == 'rating') {
        $order = ' ORDER BY imdbRating DESC';
        $url.= 'order/rating/';
    } elseif($_GET['order_by'] == 'views') {
        $order = ' ORDER BY views DESC';
        $url.= 'order/views/';
    } else {
        $order = ' ORDER BY added_date DESC';
    }
} else {
    $order = '';
}
$url.='page/';

$limit = 20;
$offset = ($page * $limit) - $limit;

$statement = "media WHERE tv=0";
$statement .= $order;

$sql = "SELECT * FROM $statement LIMIT $offset, $limit";
?>

<!DOCTYPE html>
<html>
<head>
<title>Movie List</title>
<link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script defer type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <style>
            html {
                width: 100vw;
                max-width: 100%;
            }
            body {
                margin: 0;
                background: #262626;
                opacity: 0.9;
                display: block;
            }
            .item-card {
                position: relative;
                width: 250px;
                margin: 10px;
            }
            .poster img{
                width: 250px;
                height: 375px;
            }
            .hover {
                opacity: 0;
                position: absolute;
                top: 0px;
                left: 0px;
                background: grey;
                width: 250px;
                height: 375px;
                z-index: 1;
                overflow: hidden;
            }
            .hover:hover {
                animation: change 0.5s linear 1 normal forwards;
            }
            @keyframes change {
                from {opacity:0}
                to {opacity:0.9}
            }
            .poster .year {
                position: absolute;
                top: 5px;
                right: 5px;
                background: grey;
                font-size: 25px;
                color: #ffffff;
                padding: 5px;
                opacity: 0.9;
            }
            .poster .rating{
                position: absolute;
                top: 5px;
                left: 5px;
                font-size: 25px;
                background: white;
                opacity: 0.9;
                color: black;
                padding: 5px
            }
            .hover .label {
                display: block;
                font-weight: bold;
            }
            .hover .duration .label {
                display: inline;
            }
            .poster img{
                width: 100%;
            }
            .hover .description,
            .hover .directors,
            .hover .genres,
            .hover .duration,
            .hover .actors{
                margin: 10px;
                font-family: sans-serif;
            }
            .item-card .title{
                font-family: sans-serif;
                color: black;
                text-align: center;
                padding: 10px;
            }
            .item-card .title a {
                text-decoration: none;
                color: black;
            }
            .btn1{
                padding: 0;
                margin: 10px;
                position: absolute;
                top: 50%;
                font-size: xx-large;
                border-radius: 100px;
                cursor:pointer;
                border: 5px solid #262626;
                color: blanchedalmond;
                transition: 1.5s;
                transform: translateY(-50%);
                background: #262626;
            }
            .btn1:hover{
                box-shadow: 0 5px 50px 0 #e60000 inset, 0 5px 50px 0 #e60000;
                text-shadow: 0 0 5px #e60000,0 0 5px #e60000;
            }
            .btn2:hover{
                box-shadow: 0 5px 50px 0 #e60000 inset, 0 5px 50px 0 #e60000;
                text-shadow: 0 0 5px #e60000,0 0 5px #e60000;
            }
            .btn2{
                padding: 0;
                margin: 10px;
                position: absolute;
                top: 50%;
                right: 0;
                font-size: xx-large;
                cursor:pointer;
                border-radius: 100px;
                border: 5px solid #262626;
                color: blanchedalmond;
                transition: 1.5s;
                transform: translateY(-50%);
                background: #262626;
            }
            .container {
                position: relative;
                min-width: 1240px;
                background-color: white;
                margin: 20px;
            }
            .row {
                position: relative;
                margin: 100px 60px 20px 60px;
                width: 1120px;
                border: 10px solid grey;
                border-radius: 20px;
                padding: 0 20px;
            }
            .fill h1 {
                position: absolute;
                left: 20px;
                top: 20px;
                padding: 10px;
                background-color: red;
                border-radius: 5px;
                margin: 0;
                font-family: sans-serif;
            }
            .fill a {
                position: absolute;
                right: 20px;
                top: 20px;
                padding: 10px;
                background-color: red;
                text-decoration: none;
                border-radius: 5px;
                font-family: sans-serif;
                color: black;
            }
            .content .banner {
                padding: 0;
                justify-content: center;
                padding-top: 120px;
                flex-direction: column;
            }
            .content .sci {
                position: fixed;
                right: 10px;
            }
            #pagingg {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
            }
            #pagingg ul.pagination{
                margin:0px;
                padding:0px;
                height:100%;
                overflow:hidden;
                font:12px 'Tahoma';
                list-style-type:none;	
            }

            #pagingg ul.pagination li.details{
                padding:7px 10px 7px 10px;
                font-size:14px;
            }
            #pagingg ul.pagination li{
                float:left;
                margin:0px;
                padding:0px;
                margin-left:5px;
            }
            #pagingg ul.pagination li:first-child{
                margin-left:0px;
            }
            #pagingg ul.pagination li a{
                color:white;
                display:block;
                text-decoration:none;
                padding:7px 10px 7px 10px;
                
            }
            #pagingg ul.pagination li a img{
                border:none;
            }
            ul.pagination li.details{
                color:black;
            }    
            ul.pagination li a
            {
                border-radius:3px;	
                -moz-border-radius:3px;
                -webkit-border-radius:3px;
                padding:6px 9px 6px 9px;
            }
            ul.pagination li a
            {
                color: white;
                background:#860f06;
            }	
                
            ul.pagination li a:hover,
            ul.pagination li a.current
            {
                color:white;
                background:#f31a0b;
            }
            .container.list {
                padding-top: 50px;
                display: flex;
                justify-content: space-around;
                flex-direction: row;
                flex-wrap: wrap;
                margin: 20px 80px;
            }
            .orderby {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                justify-content: center;
                flex-direction: row;
            }
            .orderby li {
                list-style: none;
            }
            .orderby li a {
                text-decoration: none;
                margin: 20px;
                color: white;
                background-color: #860f06;
                padding: 5px;
            }
            .orderby li a:hover {
                background-color: #f31a0b;
            }
    </style>
</head>
<?php include('conn.php');?>
<body>
    <header>
        <a href="http://localhost/web/home" class="logo"><img src="http://localhost/web/img/logo.png"></a>   
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
        <?php 
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
        <ul class="sci">
            <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
            <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
            <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
        </ul>
        <div class="container list">
            <ul class="orderby">
                <li><a href="http://localhost/web/movies/">Recently Added</a></li>
                <li><a href="http://localhost/web/movies/order/release/">Latest</a></li>
                <li><a href="http://localhost/web/movies/order/views/">Most Watched</a></li>
                <li><a href="http://localhost/web/movies/order/rating/">Highest Rated</a></li>
            </ul>
            <?php       $res = $conn->query($sql);
                        while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                            $tmdbID = $row['tmdbID'];
                            $title = $row['title'];
                            $poster = 'https://image.tmdb.org/t/p/w342'.$row['poster'];
                            $year = $row['year'];
                            $slug = $row['slug'];
                            
                            $description = $row['description'];
                            $imdbRating = $row['imdbRating'];
                            $duration = $row['duration'];
                            
                            $genre = explode(',',$row['genre']);
                            
                            $genres = [];
                            foreach($genre as $g) {
                                $sql = "SELECT name FROM genre WHERE id='$g'";
                            
                                $resg = $conn->query($sql);
                                $rowg = $resg->fetch_array(MYSQLI_ASSOC);
                                array_push($genres,$rowg['name']);
                            }
                            $genres = implode(', ',$genres);
                            
                            //Directors
                            $sql = "SELECT name FROM directors INNER JOIN directs ON directors.directorID = directs.directorID AND directs.tmdbID = '$tmdbID' LIMIT 1";
                            $resd = $conn->query($sql);
                            $rowd = $resd->fetch_array(MYSQLI_ASSOC);
                            $directors = $rowd['name'];
                            
                            //Actors
                            $sql = "SELECT name FROM actors INNER JOIN acts ON actors.actorID = acts.actorID AND acts.tmdbID = '$tmdbID' LIMIT 5";
                            $resa = $conn->query($sql);
                            $actors = [];
                            while($rowa = $resa->fetch_array(MYSQLI_ASSOC)) {
                                array_push($actors,$rowa['name']);
                            }
                            $actors = implode(', ',$actors);
                            ?>
                            <div class="item-card">
                                <div class="poster">
                                    <img class="img" src="<?=$poster?>">
                                    <span class="year"><?=$year?></span>
                                    <h3 class="title"><a href="http://localhost/web/movie/<?=$slug;?>/"><?=$title?></a></h3>
                                    <span class="rating"><?=$imdbRating?></span>
                                </div>
                                <div class="hover">
                                    <div class="directors">
                                        <span class="label">Directed By:</span>
                                        <span class="dir"><?=$directors?></span>
                                    </div>
                                    <div class="actors">
                                        <span class="label">Staring:</span>
                                        <span class="act"><?=$actors?></span>
                                    </div>
                                    <div class="genres">
                                        <span class="label">Genres:</span>
                                        <span class="genre"><?=$genres?></span>
                                    </div>
                                    <div class="description">
                                        <span class="label">Description:</span>
                                        <span class="desc"><?=$description?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

            <?php
            echo "<div id='pagingg' >";
            echo pagination($conn,$statement,$limit,$page,$url);
            echo "</div>";
            ?>
        </div>
    </div>
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