<?php include('conn.php');
if(isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    $sql = "SELECT * FROM media WHERE tv=1 AND slug = '$slug'";

    $res = $conn->query($sql);

    if($res->num_rows == 1) {
        $row = $res->fetch_array(MYSQLI_ASSOC); 
        $tmdbID = $row['tmdbID'];
        $title = $row['title'];
        $background = 'https://image.tmdb.org/t/p/original'.$row['background'];
        $imdbRating = $row['imdbRating'];
        $releaseDate = $row['releaseDate'];
        $duration = $row['duration'];
        $genre = $row['genre'];
        $description = $row['description'];
        $trailer = $row['trailer'];
        $year = $row['year'];
        $genre = explode(',',$row['genre']);

        $views = $row['views'];
        $views += 1;
        $conn->query("UPDATE media SET views = '$views' WHERE tmdbID = '$tmdbID'");

        $genres = [];
        foreach($genre as $g) {
            $sql = "SELECT name FROM genre WHERE id='$g'";

            $rres = $conn->query($sql);
            $roow = $rres->fetch_array(MYSQLI_ASSOC);
            array_push($genres,$roow['name']);
        }
        $genres = implode(' | ',$genres);

        if(!isset($_SESSION)) { session_start(); } 
            if(isset($_SESSION['_user_id'])) {
                $id = $_SESSION['_user_id'];
                $resultt = $conn->query("SELECT * FROM wish_list WHERE userID='$id' AND mediaID='$tmdbID'");
                if($resultt->num_rows == 1) {$fav=true;} else {$fav=false;}
            } else {
                $fav = false;
            }
        ?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Watch <?=$title?> (<?=$year?>) </title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="http://localhost/web/css/style.css">
        <script src="https://kit.fontawesome.com/f6cad20e11.js" crossorigin="anonymous"></script>
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
        <div class="background" style="background: url('<?=$background?>');background-position: center;background-size: cover;">
            <div class="banner">
                <?php $res = $conn->query("SELECT genre.name, genre.slug FROM genre INNER JOIN media ON media.genre LIKE CONCAT('%', genre.id, '%') GROUP BY genre.name ORDER BY genre.name ASC")?>
                <div class="box-genres">
                    <?php while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $name = $row['name'];
                        $slug = $row['slug'];?>
                        <div class="genre-box"><h3><a href="http://localhost/web/genre/<?=$slug?>/"><?=$name?></a></h3></div>
                    <?php } ?>
                    <div class="close-btn"><img src="http://localhost/web/img/close.png"></div>
                </div>
                <div class="movbut">
                    <a href="#" class="playBtn"><img src="http://localhost/web/img/play.png">SEASONS</a> <br><br>
                    <?php if($fav) { ?>
                        <a href="#" class="starBtn"><i class="fas fa-star"></i> FAVORITE</a><br><br>
                    <?php } else { ?>
                        <a href="#" class="starBtn"><i class="far fa-star"></i> FAVORITE</a><br><br>
                    <?php }?>
                    
                    <a href="#" class="trailerBtn">TRAILER</a><br><br>
                    <a href="#" class="castBtn">CAST</a>
                </div>
                <div class="content">
                    <h2><span><?=$title;?></span></h2>
                    <p><i>Synopsis:</i>
                    <br><?=$description;?></p>
                    <p>Genres: <?=$genres;?>
                    <br>Released: <?=$releaseDate;?>
                    <br>ImdbRating: <?=$imdbRating;?>
                    <br>Duration: <?=$duration;?> Mins</p>
                    <ul class="sci">
                        <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
                        <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
                        <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="behind">
            <div class="trailer">
                <iframe src="https://youtube.com/embed/<?=$trailer;?>?enablejsapi=1" frameborder="0" id="iframe" allow="accelerometer; encrypted-media" allowfullscreen></iframe>
            </div>
            <div class="cast">
                <h2>Directed By:</h2><br>
                <div class="directed">
                    <?php $res2 = $conn->query("SELECT * FROM directors INNER JOIN directs ON directors.directorID = directs.directorID AND directs.tmdbID = '$tmdbID'");
                        while($row2 = $res2->fetch_array(MYSQLI_ASSOC)) {
                            $name = $row2['name'];
                            $poster = ($row2['profile_photo']) ? 'https://image.tmdb.org/t/p/w185'.$row2['profile_photo'] : 'http://localhost/web/img/no-poster.jpg';?>

                            <div class="post"><img class="profile_photo" src="<?=$poster;?>"><h3><?=$name;?></h3></div>
                        <?php } ?>
                </div>
                <div style="width: 100%;height:5px;background-color:grey;margin:30px;"></div>
                <h2>Starring:</h2><br>
                <div class="directed">
                    <?php $res3 = $conn->query("SELECT * FROM actors INNER JOIN acts ON actors.actorID = acts.actorID AND acts.tmdbID = '$tmdbID'");
                        while($row3 = $res3->fetch_array(MYSQLI_ASSOC)) {
                            $name = $row3['name'];
                            $poster = ($row3['profile_photo']) ? 'https://image.tmdb.org/t/p/w185'.$row3['profile_photo'] : 'http://localhost/web/img/no-poster.jpg';
                            $character = $row3['characterName'];?>
                            <div class="post"><img class="profile_photo" src="<?=$poster;?>"><h5><?=$name;?></h5><p>(<?=$character;?>)</p></div>
                        <?php } ?>
                </div>
            </div>
            <div class="play">
                <div class="movbut1">
                    <?php $res_s = $conn->query("SELECT * FROM season WHERE tmdbID='$tmdbID' ORDER BY season ASC");
                        while($roww = $res_s->fetch_array(MYSQLI_ASSOC)) {
                            $season = $roww['season'];
                            ?>
                            <a href="#" data-id="<?=$season;?>" class="season"><h2 data-id="<?=$season;?>">SEASON <?=$season;?></h2></a>
                            <div class="episode" data-id="<?=$season;?>">
                            <?php $res_e = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season' ORDER BY episode ASC");
                            while($rowe = $res_e->fetch_array(MYSQLI_ASSOC)) {
                                $episode = $rowe['episode'];
                                $title = $rowe['title'];
                                $airdate = $rowe['airdate'];
                                $slug = $rowe['slug']; ?>
                                <a href="http://localhost/web/episode/<?=$slug?>/"><h3>Episode <?=$episode;?> - <?=$title;?> - <?=$airdate;?></h3></a>
                            <?php } ?>
                            </div>
                        <?php } ?> 
                </div>
            </div>     
            <img src="http://localhost/web/img/close.png" class="close">
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.playBtn').click(function() {
                    $('.behind').toggleClass('active');
                    $('.play').show();
                });
                $('.trailerBtn').click(function() {
                    $('.behind').toggleClass('active');
                    $('.trailer').show();
                });
                $('.castBtn').click(function() {
                    $('.behind').toggleClass('active');
                    $('.cast').addClass('active');
                    $('.directed').show();
                });
                $('.close').click(function() {
                    $('.play').hide();
                    $('.trailer').hide();
                    $('.cast').removeClass('active');
                    $('.directed').hide();
                    $('.behind').removeClass('active');
                    var iframe = document.getElementById('iframe');
                    iframe.contentWindow.postMessage('{"event":"command","func":"' + 'pauseVideo' + '","args":""}', '*');
                });
                $('.toggle-btn').on('click', function(){
                    $('.sidebar').toggleClass('active');
                });
                $('.starBtn').on('click', function(){
                    var i = $(this).find('i');
                    console.log(i);
                    if( i.hasClass('fas') ) {
                        $.post("http://localhost/web/favorite.php", {action: 'remove', tmdbID: '<?php echo $tmdbID;?>'}, function(data) {
                            if(data == 'OK') {
                                i.removeClass('fas');
                                i.addClass('far');
                            }
                            else if(data == 'Missing') {
                                alert('You Should Be Logged In To Add To Favorites');
                            }
                            else {alert('error');}
                        });
                    } else {
                        $.post("http://localhost/web/favorite.php", {action: 'add', tmdbID: '<?php echo $tmdbID;?>'}, function(data) {
                            if(data == 'OK') {
                                i.removeClass('far');
                                i.addClass('fas');
                            }
                            else if(data == 'Missing') {
                                alert('You Should Be Logged In To Add To Favorites');
                            }
                            else {alert('error');}
                        });
                    }
                });
                $(".season").on('click', function() {
                    var id = $(this).data('id');
                    $(".episode").hide();
                    $('div[data-id="'+id+'"]').show();
                    $('.season').hide();
                    $(this).show();
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
<?php    } else {
        header('Location: http://localhost/web/404/');
    }
} else {
    header('Location: http://localhost/web/404/');
}

?>