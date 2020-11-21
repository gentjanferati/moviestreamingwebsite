<?php 
    if(!isset($_SESSION)) { session_start(); } 
    if(isset($_SESSION['_user_id'])) { 
    header('Location: http://localhost/web/home/');
}?>
<!DOCTYPE html>
<html>
<head>
<title>OurMovies - Watch Movies & TV Shows Online</title>
<link rel="stylesheet" type="text/css" href="http://localhost/web/css/style.css" />
</head>
<body class="landing">
<div class="landing_header">
    <div class="landing_logo">
        <img src="http://localhost/web/img/logo.png" witdth="250px" height="80px"> 
    </div>
    <div class="landing_login">
        <span class="buton-login"><a href="http://localhost/web/login/">LOGIN</a></span>
    </div>
</div>
<div class="landing_content">
    <span>
    <h1 class="text1">Watch Movies<br>& TV shows</h1>
    </span> 
    <span>
    <h3 class="text2">Sign in now for the best experience</h3>
    </span>
    <a href="http://localhost/web/login/" id="buton1">
    <span class="anim"></span>
    <span class="anim"></span>
    <span class="anim"></span>
    <span class="anim"></span>
    Sign In
    </a>
</div>
<ul class="sci">
        <li><a href="https://facebook.com"><img src="http://localhost/web/img/facebook.png"></a></li>
        <li><a href="https://twitter.com"><img src="http://localhost/web/img/twitter.png"></a></li>
        <li><a href="https://instagram.com"><img src="http://localhost/web/img/instagram.png"></a></li>
</ul>
</body>
</html>