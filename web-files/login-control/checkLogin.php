<?php

$path_to_home = '';
while (!file_exists($path_to_home.'.login_path_anchor')) $path_to_home .= '../';
$path_to_home = $path_to_home ?: './';
include($path_to_home.'login-control/checkSession.php');

if (!$loggedin) {

  respond($message);
}
