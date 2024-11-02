<?php
session_start();

if ($_COOKIE[session_name()]) {
  $stmt = $con->prepare("UPDATE accounts SET login_code = '', last_login = NULL WHERE login_code = ?");
  $stmt->bind_param('s', $_COOKIE[session_name()]);
  $stmt->execute();
}

session_destroy();
