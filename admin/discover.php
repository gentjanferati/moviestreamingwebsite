<?php include('conn.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['_admin_username'])) { 
    $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
?>
<!DOCTYPE html>
<html>
<head>
<script src="https://kit.fontawesome.com/f6cad20e11.js" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="stylesheet.css">
<style>
    .results {
        display: flex;
        justify-content: flex-start;
        align-content: center;
        flex-wrap: wrap;
        margin: 20px auto;
    }
    .results div {
        display: block;
        width: 156px;
        margin: 10px;
        text-align: center;
        overflow:hidden;
        text-overflow: ellipsis;
        position: relative;
    }
    .results .not {
        cursor: pointer;
    }
    .title {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        cursor: initial;
    }
    img {
        border: 1px solid black;
    }
    .fas {
        top: 0;
        left: 0;
        z-index: 1000;
        position: absolute;
        color: white;
        margin: 0;
        padding: 5px;
    }
    .fa-check {
        background-color: green;
    }
    .fa-plus,
    .fa-circle-notch {
        background-color: blue;
    }
    .results .year {
        position: absolute;
        top: 0;
        right: 0;
        background: red;
        color: white;
        padding: 5px;
    }
    .pagination {
        position: relative;
        margin: 10px;
    }
</style>
<script src="../js/jquery-3.5.0.js"></script>
<script>
    //Call functions
    function add_media(element) {
        let type = element.data('type');
        let id = element.data('id');
            
        let u = '';
        if(type == 'movie') {
            u = 'add_movie.php';
        } else if(type == 'tv') {
            u = 'add_tv.php';
        } else {
            alert('Something is wrong.');
            return;
        }
        let i = element.find('i');
        element.removeClass('not');
        i.removeClass('fa-plus');
        i.addClass('fa-circle-notch');
        $.ajax({
            type: 'POST',
            url: u,
            data: {tmdb_id: id},
            success: function(data) {
                element.addClass('added');
                i.removeClass('fa-circle-notch');
                i.addClass('fa-check');
                //alert(data);
            },
            error: function(xhr) {
                i.removeClass('fa-circle-notch');
                i.addClass('fa-plus');
                element.addClass('not');
                alert('Error: '+xhr.status+' '+xhr.statusText);
            }
        }); 
    }
    $(document).ready(function(){
        $('.not').on('click', function(){
            add_media($(this));
        });
    });
    function fetch_all() {
        $('.not').each(function(){
            add_media($(this));
        });
    }
    function goToPage(num) {
        $('input[name="page"]').val(num);
        $('input[name="submited"').click();
    }
</script>
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
<br>
<br>
    <div class="form">
        <form id="form1" action="" method="POST">
            <label for="type">Type</label>
            <select name="type" id="type">
                <?php if(isset($_POST['type']) && $_POST['type'] == 'tv') { ?>
                    <option value="movie">Movie</option>
                    <option value="tv" selected>Tv</option>
                <?php } else { ?>
                    <option value="movie" selected>Movie</option>
                    <option value="tv">Tv</option>
                <?php } ?>
            </select>

            <label for="year">Year</label>
            <input type="text" name="year" id="year" pattern="\d*" maxlength="4" value="<?php if(isset($_POST['year'])) {echo $_POST['year'];} else {echo '2020';}?>">
            
            <label for="genre">Genre</label>
            <select name="genre" id="genre">
                <option value="All">All</option>
                <?php $sql_genre = "SELECT * FROM genre ORDER BY name";
                    $results = $conn->query($sql_genre);
                    $selected_genre = '';
                    if(isset($_REQUEST['genre'])) {$selected_genre=$_REQUEST['genre'];}
                    while($row = $results->fetch_array(MYSQLI_ASSOC)){
                        $id = $row['id'];
                        $name = $row['name'];
                        if($selected_genre == $id) {?>
                            <option value="<?=$id;?>" selected><?=$name;?></option>
                        <?php } else { ?>
                            <option value="<?=$id;?>"><?=$name;?></option>
                    <?php }
                    }
                ?>
            </select>

            <input type="text" name="search" id="search" minlength="3" placeholder="Search..." <?php if(isset($_POST['search'])) echo 'value="'.$_POST["search"].'"';?>>
            <input type="hidden" name="page" id="page" value="<?= $page;?>">
            <input type="submit" name="submited" value="Search">

            <?php if(isset($_POST['submited'])) {?>
                <button type="button" onclick="fetch_all()">Fetch All</button>
            <?php }?>
        </form>
    </div>
    <div class="results posters">
        <?php if(isset($_REQUEST['type']) && isset($_REQUEST['year'])) {
            $type = $_REQUEST['type'];
            $year = $_REQUEST['year'];
            
            $genre = '';
            $year_parameter	= ( $type == 'tv' ) ? 'first_air_date_year=' : 'primary_release_year=';
            if(isset($_REQUEST['genre']) && $_REQUEST['genre'] != 'All') { 
                $genre = '&with_genres='.$_REQUEST['genre'];
            }
            if(isset($_REQUEST['search']) && $_REQUEST['search']) {
                $word = $_REQUEST['search'];
                $url = "https://api.themoviedb.org/3/search/".$type."?api_key=29b41875fd9cc24c70edbf57405c2458&query=".$word."&page=".$page."&include_adult=false";
                $url = str_replace(' ','%20',$url);
                $json = file_get_contents($url);
            } else {
                $url = "https://api.themoviedb.org/3/discover/".$type."?api_key=29b41875fd9cc24c70edbf57405c2458&sort_by=popularity.desc&include_adult=false&include_video=false&".$year_parameter.$year.$genre."&page=".$page;
                $json = file_get_contents($url);
            }
            $data = json_decode($json);
            foreach($data->results as $r) {
                $tmdb_id = $r->id;
                $title = $type == 'tv' ? $r->name : $r->title;
                $poster_path = $r->poster_path ? 'https://image.tmdb.org/t/p/w154'.$r->poster_path : 'http://localhost/web/img/no-poster.jpg';
                $year = $type == 'tv'? substr($r->first_air_date,0,4) : substr($r->release_date,0,4);
                if($year == null || $year == '') $year = 'N/A'; 
                $sql = "SELECT * FROM media WHERE tmdbID = '$tmdb_id'";
                $res = $conn->query($sql);
                $added = false;
                if($res->num_rows == 1) {
                    $added = true;
                } ?>
                <div class="<?php if($added) echo 'added'; else echo 'not';?>" data-type="<?= $type?>" data-id="<?= $tmdb_id;?>">
                    <img src='<?= $poster_path;?>' width="154px" height="231px">
                    <span class="title"><?= $title;?></span>
                    <span class="year"><?= $year?></span>
                    <?php if($added) { ?>
                    <i class="fas fa-check"></i>
                    <?php } else {?>
                    <i class="fas fa-plus"></i>
                    <?php } ?>
                </div>
                <br>
            <?php }
            $total_pages = $data->total_pages; ?>
            <?php } ?>
    </div>
    <?php if(isset($_POST['submited'])) {?>
        <ul class="pagination">
            <li><a onclick="goToPage('1')">First</a></li>
            <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
                <a onclick="goToPage('<?php if($page <= 1){ echo '1'; } else { echo $page-1; } ?>')">Prev</a>
            </li>
            <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
                <a onclick="goToPage('<?php if($page >= $total_pages){ echo $total_pages; } else { echo $page+1; } ?>')">Next</a>
            </li>
            <li><a onclick="goToPage('<?php echo $total_pages; ?>')">Last</a></li>
        </ul> 
    <?php }?>
</body>
</html>
<?php } else {
    die('Unauthorized access.');
}?>