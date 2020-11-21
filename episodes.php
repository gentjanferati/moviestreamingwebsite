<?php
if (!isset($_SESSION)) {
    session_start();
}
include('conn.php');
include('some_functions.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$url = 'http://localhost/web/episodes/';

if (isset($_GET['order_by'])) {
    if ($_GET['order_by'] == 'release') {
        $order = ' ORDER BY airdate DESC, tmdbID DESC, season DESC, episode DESC';
        $url .= 'order/release/';
    } else {
        $order = ' ORDER BY added_date DESC';
    }
} else {
    $order = '';
}
$url .= 'page/';

$limit = 20;
$offset = ($page * $limit) - $limit;

$statement = "episode";
$statement .= $order;

$sql_ep = "SELECT * FROM $statement LIMIT $offset, $limit";
?>

<!DOCTYPE html>
<html>

<head>
    <title>Watch Episode List</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script defer type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>
<?php include('conn.php'); ?>

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
        <div class="banner">
            <ul class="sci">
                <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
                <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
                <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
            </ul>
            <div class="container list">
                <ul class="orderby">
                    <li><a href="http://localhost/web/episodes/">Recently Added</a></li>
                    <li><a href="http://localhost/web/episodes/order/release/">Latest</a></li>
                </ul>
                <?php $res2 = $conn->query($sql_ep);
                while ($row = $res2->fetch_array(MYSQLI_ASSOC)) {
                    $tmdbID = $row['tmdbID'];
                    $season = $row['season'];
                    $episode = $row['episode'];
                    $title = $row['title'];
                    $res_poster = $conn->query("SELECT * FROM season WHERE tmdbID='$tmdbID'");
                    $poster = $res_poster->fetch_array(MYSQLI_ASSOC)['poster'];
                    $poster = 'https://image.tmdb.org/t/p/w342' . $poster;

                    $res_title = $conn->query("SELECT * FROM media WHERE tmdbID='$tmdbID'");
                    $show_title = $res_title->fetch_array(MYSQLI_ASSOC)['title'];
                    $slug = $row['slug'];

                    $description = $row['description'];
                    $airdate = $row['airdate'];

                ?>
                    <div class="item-card">
                        <div class="poster">
                            <a href="http://localhost/web/episode/<?= $slug; ?>/"><img class="img" src="<?= $poster ?>"></a>
                            <h3 class="title"><a href="http://localhost/web/episode/<?= $slug; ?>/"><?php echo $show_title . ': ' . $season . '-' . $episode; ?></a></h3>
                        </div>
                        <div class="hover">
                            <div class="title">

                            </div>
                            <div class="description">
                                <span class="label">Episode Title:</span>
                                <span class="desc"><?= $title ?></span>
                            </div>
                            <div class="description">
                                <span class="label">Airdate:</span>
                                <span class="desc"><?= $airdate ?></span>
                            </div>
                            <div class="description">
                                <span class="label">Description:</span>
                                <span class="desc"><?= $description ?></span>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <?php
                echo "<div id='pagingg' >";
                echo pagination($conn, $statement, $limit, $page, $url);
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.toggle-btn').on('click', function() {
                $('.sidebar').toggleClass('active');
            });
            $('#genre').click(function() {
                $('.box-genres').addClass('active');
                $('.sidebar').removeClass('active');
            });
            $('.close-btn').click(function() {
                $('.box-genres').removeClass('active');
            });
        });
    </script>
</body>

</html>