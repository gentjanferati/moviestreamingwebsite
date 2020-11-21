<?php
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Homepage</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script defer type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</head>
<?php include('conn.php'); ?>

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
        <div class="banner">
            <ul class="sci">
                <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
                <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
                <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
            </ul>
            <div class="container">
                <div class="fill">
                    <h1>Latest Movies</h1>
                </div>
                <div class="fill"><a href="http://localhost/web/movies/">View All</a></div>
                <input type="button" value="◄" class="btn1">
                <input type="button" value="►" class="btn2">
                <div class="row">
                    <?php $sql = "SELECT * FROM media WHERE tv=0 ORDER BY releaseDate DESC LIMIT 10";
                    $res = $conn->query($sql);
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $tmdbID = $row['tmdbID'];
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
                                <h3 class="title"><a href="http://localhost/web/movie/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <span class="rating"><?= $imdbRating ?></span>
                            </div>
                            <div class="hover">
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
            <?php if (!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION['_user_id'])) {
                $user = $_SESSION['_user_id'];
                $r = $conn->query("SELECT * FROM media INNER JOIN wish_list ON media.tmdbID = wish_list.mediaID AND wish_list.userID='$user' AND media.tv=0");
                if ($r->num_rows > 4) { ?>
                    <div class="container">
                        <div class="fill">
                            <h1>Favorite Movies</h1>
                        </div>
                        <div class="fill"><a href="http://localhost/web/movies/">View All</a></div>
                        <input type="button" value="◄" class="btn1">
                        <input type="button" value="►" class="btn2">
                        <div class="row">
                            <?php
                            while ($row_fav = $r->fetch_array(MYSQLI_ASSOC)) {
                                $tmdbID = $row_fav['tmdbID'];
                                $title = $row_fav['title'];
                                $poster = 'https://image.tmdb.org/t/p/w342' . $row_fav['poster'];
                                $year = $row_fav['year'];
                                $slug = $row_fav['slug'];

                                $description = $row_fav['description'];
                                $imdbRating = $row_fav['imdbRating'];
                                $duration = $row_fav['duration'];

                                $genre = explode(',', $row_fav['genre']);

                                $genres = [];
                                foreach ($genre as $g) {
                                    $sql = "SELECT name FROM genre WHERE id='$g'";

                                    $resg2 = $conn->query($sql);
                                    $rowg2 = $resg2->fetch_array(MYSQLI_ASSOC);
                                    array_push($genres, $rowg2['name']);
                                }
                                $genres = implode(', ', $genres);

                                //Directors
                                $sql = "SELECT name FROM directors INNER JOIN directs ON directors.directorID = directs.directorID AND directs.tmdbID = '$tmdbID' LIMIT 1";
                                $resd2 = $conn->query($sql);
                                $rowd2 = $resd2->fetch_array(MYSQLI_ASSOC);
                                $directors = $rowd2['name'];

                                //Actors
                                $sql = "SELECT name FROM actors INNER JOIN acts ON actors.actorID = acts.actorID AND acts.tmdbID = '$tmdbID' LIMIT 5";
                                $resa2 = $conn->query($sql);
                                $actors = [];
                                while ($rowa2 = $resa2->fetch_array(MYSQLI_ASSOC)) {
                                    array_push($actors, $rowa2['name']);
                                }
                                $actors = implode(', ', $actors);
                            ?>
                                <div class="item-card">
                                    <div class="poster">
                                        <img class="img" src="<?= $poster ?>">
                                        <span class="year"><?= $year ?></span>
                                        <h3 class="title"><a href="http://localhost/web/movie/<?= $slug; ?>/"><?= $title ?></a></h3>
                                        <span class="rating"><?= $imdbRating ?></span>
                                    </div>
                                    <div class="hover">
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
            <?php  }
            }
            ?>
            <div class="container">
                <div class="fill">
                    <h1>Latest Tv Shows</h1>
                </div>
                <div class="fill"><a href="http://localhost/web/tvshows/">View All</a></div>
                <input type="button" value="◄" class="btn1">
                <input type="button" value="►" class="btn2">
                <div class="row">
                    <?php $sql = "SELECT * FROM media WHERE tv=1 ORDER BY releaseDate DESC LIMIT 10";
                    $res = $conn->query($sql);
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
                        $tmdbID = $row['tmdbID'];
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
                                <h3 class="title"><a href="http://localhost/web/tv/<?= $slug; ?>/"><?= $title ?></a></h3>
                                <span class="rating"><?= $imdbRating ?></span>
                            </div>
                            <div class="hover">
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
            <div class="container">
                <div class="fill">
                    <h1>Latest Episodes</h1>
                </div>
                <div class="fill"><a href="http://localhost/web/episodes/">View All</a></div>
                <input type="button" value="◄" class="btn1">
                <input type="button" value="►" class="btn2">
                <div class="row">
                    <?php $sql = "SELECT * FROM episode ORDER BY airdate DESC, tmdbID, episode DESC LIMIT 20";
                    $res = $conn->query($sql);
                    while ($row = $res->fetch_array(MYSQLI_ASSOC)) {
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
                </div>
            </div>
        </div>
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
        });
    </script>
</body>

</html>