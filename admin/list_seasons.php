<?php include('conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(!isset($_SESSION['_admin_username'])) { die('Unauthorized Access'); }

if(isset($_GET['tmdbID'])) {
    $tmdbID = $_GET['tmdbID'];

    $sql = "SELECT * FROM season WHERE tmdbID='$tmdbID'";
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
        <a href="add_season.php?tmdbID=<?=$tmdbID;?>&season=<?=($res->num_rows+1)?>">Add New TV Season</a>
        <div class="srcbttn" onclick="submitSearch()">
            <i class="fas fa-search"></i>
        </div>
    </div>
    <div class="results">
        <table class="results-table"> 
            <tr>
                <th>Season Number</th>
                <th>Added Date</th>
                <th>Episodes</th>
                <th>Edit</th>
                <th>Remove</th>
            </tr>
            <?php if($res->num_rows > 0) {
                while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                    $season = $row['season'];
                    $added = $row['added_date']; 
                    
                    $res2 = $conn->query("SELECT * FROM episode WHERE tmdbID='$tmdbID' AND season='$season'");
                    ?>
                    <tr data-id="<?=$tmdbID;?>" data-season="<?=$season;?>">
                        <td><?=$season;?></td>
                        <td><?=$added;?></td>
                        <td><?=$res2->num_rows;?></td>
                        <td><a href="edit_season.php?tmdbID=<?=$tmdbID;?>&season=<?=$season;?>">Edit</a></td>
                        <td><a class="remove" data-id="<?=$tmdbID;?>" data-season="<?=$season;?>" href="#">Remove</a></td>
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
            var r = confirm("Are you sure you want to delete this season?");
            if(r == true) {
                var r2 = confirm("Do you want to remove even it's episodes?");
                if(r2 == true) {
                    $.post('edit_season.php', {tmdbID: id, season: s, action: 'delete',type: '1'}, function(data){
                    console.log(data);
                    if(data == 'Removed Successfully') {
                        $('tr[data-season="'+s+'"]').remove();
                    }
                });
                } else {
                    $.post('edit_season.php', {tmdbID: id, season: s, action: 'delete', type: '0'}, function(data){
                    console.log(data);
                    if(data == 'Removed Successfully') {
                        $('tr[data-season="'+s+'"]').remove();
                    }
                });
                }
                
            }
        });
    });
</script>
</body>
</html>
<?php } else { echo 'TV Show Not Found';}