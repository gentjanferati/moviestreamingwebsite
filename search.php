<?php
if (!isset($_SESSION)) {
    session_start();
}
include('conn.php');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Search</title>
    <link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
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
            <?php if (isset($_GET['keyword']) && $_GET['keyword']) {
                $keyword = $_GET['keyword']; 
                ?>                
                <div class="search">
                    <form method="GET" action="http://localhost/web/search">
                        <input type="text" name="keyword" placeholder="Search..." minlength="3" value="<?= $keyword; ?>" required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <?php $sql = "SELECT * FROM media WHERE title LIKE '%$keyword%'";
                $res = $conn->query($sql);
                if ($res->num_rows > 0) { ?>
                    <div class="container list">
                        <h1>Results:</h1>
                        <?php $res = $conn->query($sql);
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
                                    <?php if($tv == '1'){?>
                                        <h3 class="title"><a href="http://localhost/web/tv/<?= $slug; ?>/"><?= $title ?></a></h3>
                                    <?php } else { ?>
                                        <h3 class="title"><a href="http://localhost/web/movie/<?= $slug; ?>/"><?= $title ?></a></h3>
                                    <?php } ?>
                                    
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
                <?php } else { ?>
                    <div class="user-message">
                        <h1>No Results</h1>
                    </div>
                <?php }
            } else { ?>
                <div class="search">
                    <form method="GET" action="http://localhost/web/search">
                        <input type="text" name="keyword" placeholder="Search..." minlength="3" required>
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            <?php } ?>
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