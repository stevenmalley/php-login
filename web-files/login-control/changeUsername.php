<?php

include_once("connection.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin'])) {
  respond('Please log in to change username');
} else {

  $_POST = json_decode(file_get_contents('php://input'), true);

  // Now we check if the data was submitted, isset() function will check if the data exists.
  if (!isset($_POST['username']) || empty($_POST['username'])) {
    // Could not get the data that should have been sent.
    respond('Please supply a new username');
  }

  include('validateUsername.php');

  // We need to check if the account with that username exists.
  if ($stmt = $con->prepare('SELECT id FROM accounts WHERE username = ?')) {
    // Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();
    // Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
      // Username already exists
      respond('Username taken, please choose another');
    } else {

      // prevent changing the username of an unactivated account
      include('checkActivated.php');

      // Username and email doesn't exist, update account
      if ($stmt = $con->prepare('UPDATE accounts SET username = ? WHERE id = ?')) {
        $stmt->bind_param('si', $_POST['username'], $_SESSION['id']);
        $stmt->execute();
      
        $_SESSION['username'] = $_POST['username'];
        $_SESSION['message'] = "Your username has been changed.";
        respond($_SESSION['message'],true);
      } else {
        respond('Could not prepare statement!');
      }
    }
    $stmt->close();

  } else {
    // Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
    respond('Could not prepare statement!');
  }
  $con->close();

}

?>