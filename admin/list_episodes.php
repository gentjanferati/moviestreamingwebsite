<?php include('conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(!isset($_SESSION['_admin_username'])) { die('Unauthorized Access'); }

if(isset($_GET['tmdbID']) && isset($_GET['season'])) {
    $tmdbID = $_GET['tmdbID'];
    $season = $_GET['season'];

    $sql = "SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season'";
    $res = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
 <title>Admin</title>
 <link rel="stylesheet" type="text/css" href="stylesheet.css">
 <script src="../js/jquery-3.5.0.js"></script>
 <script src="https://kit.fontawesome.com/f6cad20e11.js" crossorigin="anonymous"></script>
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
<div class="content list">
    <div class="top-bar">
        <a href="add_episode.php?tmdbID=<?=$tmdbID;?>&season=<?=$season;?>&episode=<?=($res->num_rows+1)?>">Add New TV Episode</a>
    </div>
    <div class="results">
        <table class="results-table"> 
            <tr>
                <th>Episode Number</th>
                <th>Added Date</th>
                <th>Title</th>
                <th>Edit</th>
                <th>Remove</th>
            </tr>
            <?php if($res->num_rows > 0) {
                while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                    $episode = $row['episode'];
                    $added = $row['added_date'];
                    $title = $row['title'];
                    ?>
                    <tr data-id="<?=$tmdbID;?>" data-season="<?=$season;?>" data-episode="<?=$episode;?>">
                        <td><?=$episode;?></td>
                        <td><?=$added;?></td>
                        <td><?=$title;?></td>
                        <td><a href="edit_episode.php?tmdbID=<?=$tmdbID;?>&season=<?=$season;?>&episode=<?=$episode;?>">Edit</a></td>
                        <td><a class="remove" data-id="<?=$tmdbID;?>" data-season="<?=$season;?>" data-episode="<?=$episode;?>" href="#">Remove</a></td>
                    </tr>
                <?php } 
            } else {
                echo '<tr><td colspan="5" class="no-results">No Results Found</td></tr>';
            } ?>
        </table>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('.remove').on('click', function() {
            let id = $(this).data('id');
            let s = $(this).data('season');
            let e = $(this).data('episode');
            var r = confirm("Are you sure you want to delete this episode?");
            if(r == true) {
                $.post('edit_episode.php', {tmdbID: id, season: s, episode: e, action: 'delete'}, function(data){
                    console.log(data);
                    if(data == 'Removed Successfully') {
                        $('tr[data-episode="'+e+'"]').remove();
                    }
                });
            }
        });
    });
</script>
</body>
</html>
<?php } else { echo 'Season Not Found';}