<?php

include_once("connection.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin'])) {
  respond('Please log in to change your password');
} else {

  $_POST = json_decode(file_get_contents('php://input'), true);

  // Now we check if the data was submitted, isset() function will check if the data exists.
  if (!isset($_POST['old-password'], $_POST['password'], $_POST['password2']) ||
      empty($_POST['old-password']) || empty($_POST['password']) || empty($_POST['password2'])) {
    respond('Please supply your old password and type your new password in both boxes');
  }



  include('validatePassword.php');

    // Prepare our SQL, preparing the SQL statement will prevent SQL injection.
  if ($stmt = $con->prepare("SELECT password, activated FROM accounts WHERE id = ?")) {
    // Bind parameters (s = string, i = int, b = blob, etc), in our case the email is a string so we use "s"
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    // Store the result so we can check if the account exists in the database.
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($password, $activated);
      $stmt->fetch();
      if (password_verify($_POST['old-password'], $password)) {
        if ($activated) {

          // update password
          if ($stmt = $con->prepare('UPDATE accounts SET password = ? WHERE id = ?')) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt->bind_param('si', $password, $_SESSION['id']);
            $stmt->execute();
            $stmt->close();

            $_SESSION['message'] = "Your password has been reset.";
            respond($_SESSION['message'],true);
          }
        } else {
          respond('Account not yet activated. Please check your email and click the link.');
        }
      } else {
        // Incorrect password
        respond('Incorrect password');
      }
    } else {
      respond('Session ID error'); // should not be possible
    }
    
  } else {
    respond('Could not prepare statement');
  }
  
  $con->close();
}

?>