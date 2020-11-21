<?php 
if(!isset($_SESSION)) { session_start(); } 
include('conn.php');
include('some_functions.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$url = 'http://localhost/web/tvshows/';

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

$statement = "media WHERE tv=1";
$statement .= $order;

$sql = "SELECT * FROM $statement LIMIT $offset, $limit";
?>

<!DOCTYPE html>
<html>
<head>
<title>TvShows List</title>
<link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script defer type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>
<?php include('conn.php');?>
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
                <li><a href="http://localhost/web/tvshows/">Recently Added</a></li>
                <li><a href="http://localhost/web/tvshows/order/release/">Latest</a></li>
                <li><a href="http://localhost/web/tvshows/order/views/">Most Watched</a></li>
                <li><a href="http://localhost/web/tvshows/order/rating/">Highest Rated</a></li>
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
                                    <h3 class="title"><a href="http://localhost/web/tv/<?=$slug;?>/"><?=$title?></a></h3>
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