<?php include('conn.php');
include('some_functions.php');

if(!isset($_SESSION)) 
{ 
    session_start(); 
}

if(!isset($_SESSION['_admin_username'])) die('Unauthorized Access');

if(isset($_POST['submit'])) {
  $id = isset($_POST['id']) ? $_POST['id'] : '';
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $status = isset($_POST['status']) ? $_POST['status'] : '';
  $expiry = isset($_POST['expirydate']) ? $_POST['expirydate'] : '';

  $sql = "UPDATE user SET  name='$name',
                            status='$status',
                            expirydate='$expiry'
          WHERE id = '$id'";

  if($conn->query($sql)) {
    $_SESSION['_success_msg'] = "<div class='success'>Changes Were Saved With Success</div>";
    header('Location: edit_user.php?id='.$id);
    exit();
  } else {
    $_SESSION['_success_msg'] = "<div class='failed'>There Has Been An Error</div>";
    header('Location: edit_user.php?id='.$id);
    exit();
  }
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM user WHERE id='$id'";
  $res = $conn->query($sql);
  if($res->num_rows !== 1) { 
    echo 'User Not Found';
    header('Location: list_users.php');
    exit();
  }

  $row = $res->fetch_array(MYSQLI_ASSOC);
  $name = $row['name'];
  $status = $row['status'];
  $expiry = $row['expirydate'];

?>
<!DOCTYPE html>
<html>
<head>
 <title>Admin</title>
 <link rel="stylesheet" type="text/css" href="stylesheet.css">
 <script src="../js/jquery-3.5.0.js"></script>
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

<div class="content-single">
    <?php 
    if(isset($_SESSION['_success_msg'])) {      
      echo $_SESSION['_success_msg'];
      unset($_SESSION['_success_msg']);
    }?>
      <form method="POST"> 
        <div class="left-col">

            <input type="hidden" name="id" value="<?=$id;?>">
            <label>Name</label>
            <input type="text" name="name" value="<?=$name;?>">
            <br>
            <label>Status</label>
            <select name="status">
                <?php if($status == 'Subscribed') {?>
                    <option value="Subscribed" selected>Subscribed</option>
                    <option value="Not Subscribed">Not Subscribed</option>
                <?php } else { ?>
                    <option value="Subscribed">Subscribed</option>
                    <option value="Not Subscribed" selected>Not Subscribed</option>
                <?php } ?>
            </select>
            <br>
            <input type="date" name="expirydate" value="<?=$expiry;?>">
            <br>

            <input type="submit" name="submit" value="Save">
        </div>
      </form>
</div>
<script>
$(document).ready(function(){
  $('#buton1').on('click', function(){
    $('#dropdown1').toggle();
  });
  $('.genre-item').on('click', function() {
    $(this).toggleClass('selected');
    var checkbox = $(this).find('input');
    checkbox.prop("checked", !checkbox.prop("checked"));
  });
  $('.fetchBtn').on('click', function() {
    let id=$('#tmdb').val();
    $.ajax({
      method: 'GET',
      url: 'https://api.themoviedb.org/3/movie/'+id+'?api_key=29b41875fd9cc24c70edbf57405c2458&append_to_response=trailers,credits,images',
      success: function(data) {
        $('input[name="title"]').val(data['title']);
        $('#description').val(data['overview']);
        $('input[name="poster"]').val(data['poster_path']);
        $('input[name="background"]').val(data['images']['backdrops'][0]['file_path']);
        $('input[name="trailer"]').val(data['trailers']['youtube'][0]['source']);
        $('input[name="duration"]').val(data['runtime']);
        $('input[name="release"]').val(data['release_date']);
        $('input[name="year"]').val(data['release_date'].substring(0,4));

        for(let i=0; i< data['genres'].length; i++){
          $('input[value="'+data['genres'][i]['id']+'"]').prop("checked", true);
          $('input[value="'+data['genres'][i]['id']+'"]').parent('.genre-item').addClass('selected');
        }
      },
      error: function(xhr) {
        console.log(url);
        alert('Error: '+xhr.status+' '+xhr.statusText);
      }
    });
  });
});
</script>
</body>
</html>
<?php } else {
  echo 'User Not Found';
  header('Location: list_users.php');
  exit();
}?>