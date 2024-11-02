<?php

$path_to_home = '';
while (!file_exists($path_to_home.'.login_path_anchor')) $path_to_home .= '../';
$path_to_home = $path_to_home ?: './';

if (!$loggedin) {

	header('Location: '.$path_to_home);
	exit;
}