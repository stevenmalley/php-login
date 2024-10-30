<?php

include_once('config.php');
include("../login-components/options.php");

$from    = 'noreply@'.$emailServer;
$subject = $appName.': confirm new email address';
$headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
// Update the activation variable below
$activate_link = $protocol.$server.'/activate.php?code=' . $activation_code;
$message = '<p>Please click the following link to confirm your new email address: <a href="' . $activate_link . '">' . $activate_link . '</a></p>';
mail($email, $subject, $message, $headers);


