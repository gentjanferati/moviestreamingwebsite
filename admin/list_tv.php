<?php include('conn.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

if(!isset($_SESSION['_admin_username'])) { die('Unauthorized Access'); }

$sql = "SELECT * FROM media WHERE tv=1";

if(isset($_POST['search'])) {
    $keyword = $_POST['search'];
    $sql .= " AND title LIKE '%$keyword%'"; 
}

$res1 = $conn->query($sql);

//Pagination
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$offset = ($page-1) * 20;
$total_rows =  $res1->num_rows;
$total_pages = ceil($total_rows / 20);

$sql .= " ORDER BY added_date DESC";
$sql .= " LIMIT ".$offset.", 20";

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
        <a href="discover.php">Add New TV Show</a>
        <form id="search" method="POST">
             <input type="text" name="search" placeholder="Search...">
        </form>
        <div class="srcbttn" onclick="submitSearch()">
            <i class="fas fa-search"></i>
        </div>
    </div>
    <div class="results">
        <table class="results-table"> 
            <tr>
                <th>Title</th>
                <th>Added Date</th>
                <th>Views</th>
                <th>Edit</th>
                <th>Remove</th>
            </tr>
            <?php if($res->num_rows > 0) {
                while($row = $res->fetch_array(MYSQLI_ASSOC)) {
                    $tmdbID = $row['tmdbID'];
                    $title = $row['title'];
                    $added = $row['added_date'];
                    $views = $row['views']; ?>
                    <tr data-id="<?=$tmdbID;?>">
                        <td><?=$title;?></td>
                        <td><?=$added;?></td>
                        <td><?=$views;?></td>
                        <td><a href="edit_tv.php?tmdbID=<?=$tmdbID;?>">Edit</a></td>
                        <td><a class="remove" data-id="<?=$tmdbID;?>" href="#">Remove</a></td>
                    </tr>
                <?php } 
            } else {
                echo '<tr><td colspan="5" class="no-results">No Results Found</td></tr>';
            } ?>
        </table>
    </div>
    <ul class="pagination">
        <li><a href="?page=1">First</a></li>
        <li class="<?php if($page <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($page <= 1){ echo '#'; } else { echo "?page=".($page - 1); } ?>">Prev</a>
        </li>
        <li class="<?php if($page >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($page >= $total_pages){ echo '#'; } else { echo "?page=".($page + 1); } ?>">Next</a>
        </li>
        <li><a href="?page=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
</div>
<script>
    function submitSearch() {
        var form1 = $('form#search');
        form1.submit();
    }
    $(document).ready(function(){
        $('.remove').on('click', function() {
            let id = $(this).data('id');
            var r = confirm("Are you sure you want to delete this tv show?");
            if(r == true) {
                var r2 = confirm("Do you want to remove all seasons and episodes?");
                if(r2 == true) {
                    $.post('edit_tv.php', {tmdbID: id, action: 'delete',type: '1'}, function(data){
                    console.log(data);
                    if(data == 'Removed Successfully') {
                        $('tr[data-id="'+id+'"]').remove();
                    }
                });
                } else {
                    $.post('edit_tv.php', {tmdbID: id, action: 'delete', type: '0'}, function(data){
                    console.log(data);
                    if(data == 'Removed Successfully') {
                        $('tr[data-id="'+id+'"]').remove();
                    }
                });
                }
                
            }
        });
    });
</script>
</body>
</html>