<?php
if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['_user_id'])) {
    header('Location: http://localhost/web/login/');
    exit();
}
include('conn.php');

$id = $_SESSION['_user_id'];
$r = $conn->query("SELECT * FROM user WHERE id = '$id'");
$row = $r->fetch_array(MYSQLI_ASSOC);
$displaname = $row['name'];
$email = $row['email'];
$status = $row['status'];
$expirydate = isset($row['expirydate']) ? $row['expirydate'] : '';

$r = $conn->query("SELECT * FROM wish_list WHERE userID = '$id'");
$favorites = $r->num_rows;

$r = $conn->query("SELECT * FROM watch_list WHERE userID = '$id'");
$watched = $r->num_rows;


?>
<!DOCTYPE html>
<html>

<head>
    <title>My Profile</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
    <script src="https://kit.fontawesome.com/f6cad20e11.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script defer type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
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
            <?php if (!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION['_user_id'])) { ?>
                <li><a href="http://localhost/web/profile/">Profile</a></li>
                <li><a href="http://localhost/web/logout/">Logout</a></li>
            <?php } else { ?>
                <li><a href="http://localhost/web/login/">Login</a></li>
            <?php } ?>
        </ul>
    </div>
    <div class="content">
        <?php $res = $conn->query("SELECT genre.name, genre.slug FROM genre INNER JOIN media ON media.genre LIKE CONCAT('%', genre.id, '%') GROUP BY genre.name ORDER BY genre.name ASC") ?>
        <div class="box-genres">
            <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                $name = $row['name'];
                $slug = $row['slug']; ?>
                <div class="genre-box">
                    <h3><a href="http://localhost/web/genre/<?= $slug ?>/"><?= $name ?></a></h3>
                </div>
            <?php } ?>
            <div class="close-btn"><img src="http://localhost/web/img/close.png"></div>
        </div>
        <div class="banner profile">
            <div id="profhead">
                <div class="profilpic" style="width:200px;height:200px">
                    <img src="http://localhost/web/img/profile.png" style="width:200px;height:200px;border:5px solid black;border-radius: 100px">
                </div>
                <div class="profilstat">
                    <p>Name: <?= $displaname ?></p>
                    <p>Email: <?= $email ?></p>
                    <p>Status: <?= $status ?></p>
                    <?php if ($expirydate) { ?>
                        <p>Expiry Date: <?= $expirydate ?> | <a class="profsub" href="http://localhost/web/payment/">Extend Subscription</a>
                        <?php } else { ?>
                            <p>Expiry Date: None | <a class="profsub" href="http://localhost/web/payment/">Subscribe Now</a>
                            <?php } ?>
                </div>
                <div class="profnum">
                    <b>Stats</b><br>
                    <p style="padding:5px 10px;border-radius:5px"><i class="fas fa-eye"></i> <?= $watched ?></p>
                    <p style="padding:5px 10px;border-radius:5px"><i class="far fa-heart"></i> <?= $favorites ?></p>
                </div>
            </div>
            <div class="profbut">
                <a href="#watched">History</a>
                <a href="#favorites">Favourites</a>
            </div>
        </div>
        <div class="banner">
        <?php $sql = "SELECT * FROM media INNER JOIN wish_list ON media.tmdbID = wish_list.mediaID AND wish_list.userID='$id'"; 
                  $res = $conn->query($sql);
                  if($res->num_rows >= 4) {?>
        <div class="container" id="favorites">
                <div class="fill">
                    <h1>Favorite List</h1>
                </div>
                <input type="button" value="◄" class="btn1">
                <input type="button" value="►" class="btn2">
                <div class="row">
                    <?php 
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $tmdbID = $row['tmdbID'];
                        $tv = $row['tv'];
                        $title = $row['title'];
                        $poster = 'https://image.tmdb.org/t/p/w342' . $row['poster'];
                        $year = $row['year'];
                        $slug = $row['slug'];

                        $description = $row['description'];
                        $imdbRating = $row['imdbRating'];
                        $duration = $row['duration'];

                        $genre = explode(',', $row['genre']);

                        $genres = [];
                        foreach ($genre as $g) {
                            $sql = "SELECT name FROM genre WHERE id='$g'";

                            $resg = $conn->query($sql);
                            $rowg = $resg->fetch_array(MYSQLI_ASSOC);
                            array_push($genres, $rowg['name']);
                        }
                        $genres = implode(', ', $genres);

                        //Directors
                        $sql = "SELECT name FROM directors INNER JOIN directs ON directors.directorID = directs.directorID AND directs.tmdbID = '$tmdbID' LIMIT 1";
                        $resd = $conn->query($sql);
                        $rowd = $resd->fetch_array(MYSQLI_ASSOC);
                        $directors = $rowd['name'];

                        //Actors
                        $sql = "SELECT name FROM actors INNER JOIN acts ON actors.actorID = acts.actorID AND acts.tmdbID = '$tmdbID' LIMIT 5";
                        $resa = $conn->query($sql);
                        $actors = [];
                        while ($rowa = $resa->fetch_array(MYSQLI_ASSOC)) {
                            array_push($actors, $rowa['name']);
                        }
                        $actors = implode(', ', $actors);
                    ?>
                        <div class="item-card">
                            <div class="poster">
                                <img class="img" src="<?= $poster ?>">
                                <span class="year"><?= $year ?></span>
                                <?php if($tv == 1) { ?>
                                    <h3 class="title"><a href="http://localhost/web/tv/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <?php } else { ?>
                                    <h3 class="title"><a href="http://localhost/web/movie/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <?php } ?>
                                
                                <span class="rating"><?= $imdbRating ?></span>
                            </div>
                            <div class="hover">
                                <div class="remove" data-id="<?=$tmdbID?>" data-type="favorites"> 
                                    <img src="http://localhost/web/img/close.png">
                                </div>
                                <div class="directors">
                                    <span class="label">Directed By:</span>
                                    <span class="dir"><?= $directors ?></span>
                                </div>
                                <div class="actors">
                                    <span class="label">Staring:</span>
                                    <span class="act"><?= $actors ?></span>
                                </div>
                                <div class="genres">
                                    <span class="label">Genres:</span>
                                    <span class="genre"><?= $genres ?></span>
                                </div>
                                <div class="description">
                                    <span class="label">Description:</span>
                                    <span class="desc"><?= $description ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php $sql = "SELECT * FROM media INNER JOIN watch_list ON media.tmdbID = watch_list.mediaID AND watch_list.userID='$id'"; 
                  $res = $conn->query($sql);
                  if($res->num_rows >= 4) {?>
            <div class="container" id="history">
                <div class="fill">
                    <h1>Watched History</h1>
                </div>
                <input type="button" value="◄" class="btn1">
                <input type="button" value="►" class="btn2">
                <div class="row">
                    
                    
                    <?php while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $tmdbID = $row['tmdbID'];
                        $tv = $row['tv'];
                        $title = $row['title'];
                        $poster = 'https://image.tmdb.org/t/p/w342' . $row['poster'];
                        $year = $row['year'];
                        $slug = $row['slug'];

                        $description = $row['description'];
                        $imdbRating = $row['imdbRating'];
                        $duration = $row['duration'];

                        $genre = explode(',', $row['genre']);

                        $genres = [];
                        foreach ($genre as $g) {
                            $sql = "SELECT name FROM genre WHERE id='$g'";

                            $resg = $conn->query($sql);
                            $rowg = $resg->fetch_array(MYSQLI_ASSOC);
                            array_push($genres, $rowg['name']);
                        }
                        $genres = implode(', ', $genres);

                        //Directors
                        $sql = "SELECT name FROM directors INNER JOIN directs ON directors.directorID = directs.directorID AND directs.tmdbID = '$tmdbID' LIMIT 1";
                        $resd = $conn->query($sql);
                        $rowd = $resd->fetch_array(MYSQLI_ASSOC);
                        $directors = $rowd['name'];

                        //Actors
                        $sql = "SELECT name FROM actors INNER JOIN acts ON actors.actorID = acts.actorID AND acts.tmdbID = '$tmdbID' LIMIT 5";
                        $resa = $conn->query($sql);
                        $actors = [];
                        while ($rowa = $resa->fetch_array(MYSQLI_ASSOC)) {
                            array_push($actors, $rowa['name']);
                        }
                        $actors = implode(', ', $actors);
                    ?>
                        <div class="item-card">
                            <div class="poster">
                                <img class="img" src="<?= $poster ?>">
                                <span class="year"><?= $year ?></span>
                                <?php if($tv == 1) { ?>
                                    <h3 class="title"><a href="http://localhost/web/tv/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <?php } else { ?>
                                    <h3 class="title"><a href="http://localhost/web/movie/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <?php } ?>
                                
                                <span class="rating"><?= $imdbRating ?></span>
                            </div>
                            <div class="hover">
                                <div class="remove" data-id="<?=$tmdbID?>" data-type="history"> 
                                    <img src="http://localhost/web/img/close.png">
                                </div>
                                <div class="directors">
                                    <span class="label">Directed By:</span>
                                    <span class="dir"><?= $directors ?></span>
                                </div>
                                <div class="actors">
                                    <span class="label">Staring:</span>
                                    <span class="act"><?= $actors ?></span>
                                </div>
                                <div class="genres">
                                    <span class="label">Genres:</span>
                                    <span class="genre"><?= $genres ?></span>
                                </div>
                                <div class="description">
                                    <span class="label">Description:</span>
                                    <span class="desc"><?= $description ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php }?>
        </div>
        <ul class="sci">
            <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
            <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
            <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
        </ul>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toggle-btn').on('click', function() {
                $('.sidebar').toggleClass('active');
            });
            $('.row').slick({
                infinite: true,
                slidesToScroll: 1,
                variableWidth: true,
                arrows: false
            });
            $('.btn1').on('click', function() {
                let test = $(this).parent().find('.row');
                console.log(test);
                $(this).parent().find('.row').slick('slickPrev');
            });
            $('.btn2').on('click', function() {
                $(this).parent().find('.row').slick('slickNext');
            });
            $('#genre').click(function() {
                $('.box-genres').addClass('active');
                $('.sidebar').removeClass('active');
            });
            $('.close-btn').click(function() {
                $('.box-genres').removeClass('active');
            });
            $('.remove').on('click',function() {
                var id = $(this).data('id');
                var div = $(this).closest('.item-card');
                console.log($(this));
                console.log(div);
                if($(this).data('type') == 'history') {
                    let c = confirm('Do you want to delete this media from watch history?');
                    if(c == true) {
                        $.post("http://localhost/web/history.php", {tmdbID: id, action: 'delete'}, function(data) {
                            if(data == 'OK') {
                                div.remove();
                            }
                        });
                    }
                } else if($(this).data('type') == 'favorites') {
                    let c = confirm('Do you want to delete this media from watch history?');
                    if(c == true) {
                        $.post("http://localhost/web/favorite.php", {tmdbID: id, action: 'delete'}, function(data) {
                            if(data == 'OK') {
                                div.remove();
                            }
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>