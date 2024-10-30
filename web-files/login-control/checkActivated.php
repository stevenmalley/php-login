<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (isset($_SESSION['loggedin'])) {
  // We don't have the email info stored in sessions, so instead, we can get the results from the database.
  $stmt = $con->prepare('SELECT activated FROM accounts WHERE id = ?');
  // In this case we can use the account ID to get the account info.
  $stmt->bind_param('i', $_SESSION['id']);
  $stmt->execute();
  $stmt->bind_result($activated);
  $stmt->fetch();
  $stmt->close();

  if (!$activated) {
    respond("That account has not been activated. Please check your email and click the link in it.");
    exit;
  }
}