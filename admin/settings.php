<?php include('conn.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($_SESSION['_admin_username'])) { die('Unauthorized Access');}

if(isset($_POST['submit'])) {
    if($_POST['submit'] == 'Change') {
        $old_pw = md5($_POST['old_pw']);
        $new_pw = md5($_POST['new_pw']);
        $confirm_pw = md5($_POST['confirm_pw']);

        $user = $_SESSION['_admin_username'];
        
        $res = $conn->query("SELECT * FROM admin WHERE username='$user' AND password='$old_pw'");
        if($res->num_rows != 1) {
            $_SESSION['_msg_admin_settings'] = "<div class='failed'>Old Password Is Wrong</div>";
            header('Location: admsettings.php');
            exit();
        }
        if($new_pw != $confirm_pw) {
            $_SESSION['_msg_admin_settings'] = "<div class='failed'>New Passwords Do Not Match</div>";
            header('Location: admsettings.php');
            exit();
        }
        $sql = "UPDATE admin SET password='$new_pw'";
        if($conn->query($sql)) {
            $_SESSION['_msg_admin_settings'] = "<div class='success'>Password Changed Successfully</div>";
            header('Location: admsettings.php');
            exit();
        } else {
            $_SESSION['_msg_admin_settings'] = "<div class='failed'>Error. Try Again Later</div>";
            header('Location: admsettings.php');
            exit();
        }
    } else {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
        $role = $_POST['role'];

        if($_SESSION['_admin_role'] != 1) {
            $_SESSION['_msg_admin_settings'] = "<div class='failed'>You Don't Have Authority To Add New Admins.</div>";
            header('Location: admsettings.php');
            exit();
        }
        $sql = "INSERT INTO admin(username,password,role) VALUES ('$username','$password','$role')";
        if($conn->query($sql)) {
            $_SESSION['_msg_admin_settings'] = "<div class='success'>New Admin Created Successfully.</div>";
            header('Location: admsettings.php');
            exit();
        } else {
            $_SESSION['_msg_admin_settings'] = "<div class='failed'>Error. Try Again Later</div>";
            header('Location: admsettings.php');
            exit();
        }
    }
}
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
<?php if(isset($_SESSION['_msg_admin_settings'])) { echo $_SESSION['_msg_admin_settings']; unset($_SESSION['_msg_admin_settings']);}?>
<div class="admset">
    <div id="div1">
        <form method="POST">
            <h1 class="formHeaders">Change Password</h1><br>
            <label>Old Password</label><br>
            <input type="password" name="old_pw" placeholder="Old Password" required>
            <br><br>
            <label>New Password</label><br>
            <input type="password" name="new_pw" id="new_pw" placeholder="New Password" required>
            <br><br>
            <label>Repeat New Password</label><br>
            <input type="password" name="confirm_pw" id="confirm_pw" placeholder="Repeat New Password" required><br>
            <span id='message'></span>
            <br><br>
            <input type="submit" name="submit" value="Change">
        </form>
    </div>
    <div id="div2">
        <form method="POST">
            <h1 class="formHeaders">Add New Admin</h1><br>
            <label>Username</label><br>
            <input type="text" name="username" placeholder="User Name" required>
            <br><br>
            <label>Password</label><br>
            <input type="password" name="password" placeholder="Password" required>
            <br><br>
            <label>Role</label><br>
            <select name="role">
            <option value="1" selected>Administrator</option>
            <option value="2">Moderator</option>
            <option value="3">Admin</option>
            </select>      
            <br><br>
            <input type="submit" name="submit" value="Add Admin">
        </form>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#new_pw, #confirm_pw').on('keyup', function () {
    if ($('#new_pw').val() == $('#confirm_pw').val()) {
        $('#message').html('Matching').css('color', 'green');
    } else 
        $('#message').html('Not Matching').css('color', 'red');
    });
});
</script>
</body>
</html>