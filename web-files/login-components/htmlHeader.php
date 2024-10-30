<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$path_to_origin = '';
while (!file_exists($path_to_origin.'.login_path_anchor')) $path_to_origin .= '../';
$path_to_origin = $path_to_origin ?: './';

include($path_to_origin."login-components/options.php");

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
		<link rel="stylesheet" href="<?=$path_to_origin?>libs/fa/all.min.css">
		<link rel="stylesheet" href="<?=$path_to_origin?>css/login-style.css">
    <script src="<?=$path_to_origin?>js/logout-script.js" defer></script>
    <?php
      if (isset($styles)) {
        foreach ($styles as $stylesheetHREF) {
          echo "<link rel=\"stylesheet\" href=\"$path_to_origin$stylesheetHREF\">";
        }
      }

      $loggedin = isset($_SESSION['loggedin']) ? "true" : "false";
      $username = isset($_SESSION['username']) ? "'".$_SESSION['username']."'" : "null";
      echo "<script>const loggedin = $loggedin;const username = $username;</script>";

      if (isset($scripts)) {
        foreach($scripts as $script) {
          echo "script src='".$script["path"]."' ".join(" ",$script["options"])."></script>";
        }
      }
      ?>
	</head>
	<body>
		<nav class="navtop">
			<div>
				<h1><a href="<?=$path_to_origin?>"><?=$appName?></a></h1>
        <div class="navLinks">
          <?php if (isset($_SESSION['loggedin'])) { ?>
            <a class="nav" href="<?=$path_to_origin?>profile.php"><i class="fas fa-user-circle"></i><?=htmlspecialchars($_SESSION['username'], ENT_QUOTES)?></a>
            <a class="nav logoutLink" href="<?=$path_to_origin?>"><i class="fas fa-sign-out-alt"></i>Logout</a>
          <?php } else { ?>
            <a class="nav" href="<?=$path_to_origin?>register.php"><i class="fas fa-solid fa-pen-to-square"></i>Register</a>
            <a class="nav" href="<?=$path_to_origin?>login.php"><i class="fas fa-sign-in-alt"></i>Login</a>
          <?php } ?>
        </div>
			</div>
		</nav>
		<div class="content">