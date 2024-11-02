<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$path_to_home = '';
while (!file_exists($path_to_home.'.login_path_anchor')) $path_to_home .= '../';
$path_to_home = $path_to_home ?: './';

include($path_to_home."login-components/options.php");
include($path_to_home.'login-control/checkSession.php');

$pageTitle = $pageTitle ?? $appName;
$pageDescription = $pageDescription ?? $appName;

?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?=$pageTitle?></title>
    <meta name="description" content="<?=$pageDescription?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?=$path_to_home?>libs/fa/all.min.css">
		<link rel="stylesheet" href="<?=$path_to_home?>css/login-style.css">
    <script src="<?=$path_to_home?>js/logout-script.js" defer></script>
    <?php
      if (isset($styles)) {
        foreach ($styles as $stylesheetHREF) {
          echo "<link rel=\"stylesheet\" href=\"$path_to_home$stylesheetHREF\">";
        }
      }

      $loggedinJS = isset($_SESSION['loggedin']) ? "true" : "false";
      $usernameJS = isset($_SESSION['username']) ? "'".$_SESSION['username']."'" : "null";
      echo "<script>const loggedin = $loggedinJS;const username = $usernameJS;</script>";

      if (isset($scripts)) {
        foreach($scripts as $script) {
          echo "<script src='".$script["path"]."' ".join(" ",$script["options"])."></script>";
        }
      }
      ?>
	</head>
	<body>
		<nav class="navtop">
			<div>
				<h1><a href="<?=$path_to_home?>"><?=$appName?></a></h1>
        <div class="navLinks">
          <?php if (isset($_SESSION['loggedin'])) { ?>
            <a class="nav" href="<?=$path_to_home?>profile.php"><i class="fas fa-user-circle"></i><?=htmlspecialchars($_SESSION['username'], ENT_QUOTES)?></a>
            <a class="nav logoutLink" href="<?=$path_to_home?>"><i class="fas fa-sign-out-alt"></i>Logout</a>
          <?php } else { ?>
            <a class="nav" href="<?=$path_to_home?>register.php"><i class="fas fa-solid fa-pen-to-square"></i>Register</a>
            <a class="nav" href="<?=$path_to_home?>login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
          <?php } ?>
        </div>
			</div>
		</nav>
		<div class="content">