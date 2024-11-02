<?php

$path_to_home = '';
while (!file_exists($path_to_home.'.login_path_anchor')) $path_to_home .= '../';
$path_to_home = $path_to_home ?: './';

include_once($path_to_home."login-control/connection.php");

include($path_to_home."login-components/options.php");

$preexistingSession = isset($_COOKIE[session_name()]);

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$loggedin = false;
$message = "";

if ($preexistingSession && $stmt = $con->prepare("SELECT id, username, email, UNIX_TIMESTAMP(last_login) FROM accounts WHERE login_code = ?")) {
  $stmt->bind_param('s', $_COOKIE[session_name()]);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $username, $email, $last_login);
    $stmt->fetch();

    $period = time()-$last_login;

    if ($period < $login_duration) {

      // update last_login
      if ($stmt = $con->prepare("UPDATE accounts SET last_login = NOW() WHERE login_code = ?")) {
        $stmt->bind_param('s', $_COOKIE[session_name()]);
        $stmt->execute();

        $loggedin = true;
        include($path_to_home."login-control/login.php");

      } else { // updating DB with last_login failed

        $message = "Could not prepare statement";

      }

    } else { // login expired

      session_destroy();
      $_SESSION[] = array();
      $message = "Your session has been timed out.";

      $stmt = $con->prepare("UPDATE accounts SET login_code = '', last_login = NULL WHERE login_code = ?");
      $stmt->bind_param('s', $_COOKIE[session_name()]);
      $stmt->execute();

    }

  } else { // login_code (session cookie value) not recognised

    session_destroy();
    $_SESSION[] = array();
    $_SESSION['username'] = "bad login_code";
    unset($_SESSION['loggedin']);















    $message = "You are not logged in";

  }
} else { // checking DB for last login failed

  session_destroy();
  $_SESSION[] = array();
  $message = "Could not prepare statement";
}
