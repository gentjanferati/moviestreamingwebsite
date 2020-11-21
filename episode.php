<?php include('conn.php');
include('some_functions.php');
if(isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    $sql = "SELECT * FROM episode WHERE slug = '$slug'";

    $res = $conn->query($sql);

    if($res->num_rows == 1) {
        $row = $res->fetch_array(MYSQLI_ASSOC); 
        $tmdbID = $row['tmdbID'];
        $season = $row['season'];
        $episode = $row['episode'];
        $title = $row['title'];
        $airdate = $row['airdate'];
        $url = isset($row['url']) ? $row['url'] : 'https://vjs.zencdn.net/v/oceans.mp4';
        $description = $row['description'];

        $next = null;
        $tmp = $episode + 1;
        $ress = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season' AND episode='$tmp'");
        if($ress->num_rows == 1) { $next = $ress->fetch_array(MYSQLI_ASSOC)['slug'];}
        else {
            $tmp = $season + 1;
            $ress = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$tmp' AND episode='1'");
            if($ress->num_rows == 1) { $next = $ress->fetch_array(MYSQLI_ASSOC)['slug'];}
        }

        $prev = null;
        if($season >= 1 && $episode > 1) {
            $tmp = $episode-1;
            $ress = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season' AND episode='$tmp'");
            if($ress->num_rows == 1) { $prev = $ress->fetch_array(MYSQLI_ASSOC)['slug'];}
        } else if($season > 1 && $episode == 1 ) {
            $tmp = $season-1;
            $ress = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$tmp'");
            while($r = $ress->fetch_array(MYSQLI_ASSOC)) {
                $prev = $r['slug'];
            }
        } 

        $res2 = $conn->query("SELECT title, background FROM media WHERE tmdbID='$tmdbID' AND tv=1");
        $row2 = $res2->fetch_array(MYSQLI_ASSOC);
        $background = 'https://image.tmdb.org/t/p/original'.$row2['background'];
        $tvtitle = $row2['title'];
        ?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Watch <?php echo $tvtitle.': '.$season.'-'.$episode;?></title>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="http://localhost/web/css/style.css">
        <link href="https://vjs.zencdn.net/7.7.6/video-js.css" rel="stylesheet" />
        <script src="https://vjs.zencdn.net/7.7.6/video.js"></script>
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
                <div class="movbut">
                    <a href="#" class="playBtn"><img src="http://localhost/web/img/play.png">PLAY</a> <br><br>
                    <a href="#" class="downloadBtn">DOWNLOAD</a><br><br>
                </div>
                <div class="content">
                    <h2><span><?=$title;?></span></h2>
                    <h3><?=$tvtitle;?></h3>
                    <p><i>Synopsis:</i>
                    <br><?=$description;?>
                    <br><i>Airdate:</i> <?=$airdate;?></p>
                    <ul class="sci">
                        <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
                        <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
                        <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
                    </ul>
                </div>
                <div class="prinext">
                    <?php if($prev) { ?>
                        <span type="button" value="◄" class="ep btn1" onclick="window.location.assign('http:\/\/localhost/web/episode/<?=$prev?>/')">◄ Previous Episode</span>
                    <?php } if($next) { ?>
                        <span type="button" value="►" class="ep btn2" onclick="window.location.assign('http:\/\/localhost/web/episode/<?=$next?>/')">Next Episode ►</span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="behind">
            <div class="play">
            <?php $status = userStatus($conn);
                    if($status == '0') {?>
                        <div class="user-message">
                            <h1>You need to login and have a subscription.</h1>
                            <p><a href="http://localhost/web/login/">Login</a></p>
                        </div>
                    <?php } elseif($status == '1') {?>
                        <div class="user-message">
                            <h1>You need to have a subscription.</h1>
                            <p><a href="http://localost/web/payment/">Make a payment</a></p>
                        </div>
                    <?php } elseif($status == '2') {?>
                        <video
                            style="object-fit: fill;"
                            id="player"
                            class="video-js"
                            controls
                            preload="auto"
                            width="640"
                            height="264"
                            data-setup="{}"
                        >
                            <source src="<?=$url;?>" type="video/mp4" />
                            <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a
                            web browser that
                            <a href="https://videojs.com/html5-video-support/" target="_blank"
                                >supports HTML5 video</a
                            >
                            </p>
                        </video>
                    <?php } ?>
            </div>     
            <img src="http://localhost/web/img/close.png" class="close">
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.playBtn').click(function() {
                    $('.behind').toggleClass('active');
                    $('.play').show();
                });
                $('.downloadBtn').click(function(e) {
                    e.preventDefault();
                    <?php if($status == '0') {?>
                        alert('Login And Make A Subscription');
                    <?php } elseif($status == '1') {?>
                        alert('Make A Subscription');
                    <?php } elseif($status == '2') {?>
                        window.open('<?=$url;?>', '_blank');
                    <?php } ?>
                });
                $('.close').click(function() {
                    $('.play').hide();
                    $('.behind').removeClass('active');
                    var player = videojs('player');
                    player.pause();
                });
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
            var vid = document.getElementById("player");
            vid.onplay = function() {
                var interval = setInterval(function(){
                    if((vid.currentTime/vid.duration) * 100 > 50) {
                        $.post("http://localhost/web/history.php", {tmdbID: '<?=$tmdbID?>'});
                        clearInterval(interval);
                    } else {
                        //console.log(vid.currentTime);
                    }
                }, 1000);
                
            };
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