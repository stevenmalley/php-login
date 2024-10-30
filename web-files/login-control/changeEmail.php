<?php

include_once("connection.php");
include("../login-components/options.php");

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['loggedin'])) {
  respond('Please log in to update your email address');
} else {

  $_POST = json_decode(file_get_contents('php://input'), true);

  // Now we check if the data was submitted, isset() function will check if the data exists.
  if (!isset($_POST['email']) || empty($_POST['email'])) {
    // Could not get the data that should have been sent.
    respond('Please supply a new email address');
  }

  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    respond('Email address is not valid');
  }

  // We need to check if the account with that email address exists.
  if ($stmt = $con->prepare('SELECT id, activation_code FROM accounts WHERE email = ?')) {
    $stmt->bind_param('s', $_POST['email']);
    $stmt->execute();
    $stmt->store_result();
    // Store the result so we can check if the account exists in the database.
    if ($stmt->num_rows > 0) {
      // Username already exists
      respond('That email address already has an account associated with it');
    } else {

      // prevent changing the email of an unactivated account
      include('checkActivated.php');

      if ($requireEmailAuthentication) {
        // send an email with a link to confirm new email address
        // continue using former email address until the confirmation link is clicked

        if ($stmt = $con->prepare('UPDATE accounts SET activation_code = ?, new_email = ? WHERE id = ?')) {

          function randomHexString($length) {
            $str = "";
            while ($length-- > 0) $str .= dechex(rand(0,15));
            return $str;
          }

          $activation_code = uniqid().randomHexString(10);
          $stmt->bind_param('ssi', $activation_code, $_POST['email'], $_SESSION['id']);
          $stmt->execute();

          $email = $_SESSION['email'];
          include('confirmationEmail.php');
          $_SESSION['message'] = "You should receive an email shortly. Please click the link in it to confirm your new email address. Until you click the link, you must continue to log in with your former email address.";
          respond($_SESSION['message'],true);
        }
      } else {
        // update account
        if ($stmt = $con->prepare('UPDATE accounts SET email = ? WHERE id = ?')) {
          $stmt->bind_param('si', $_POST['email'], $_SESSION['id']);
          $stmt->execute();
        
          $_SESSION['message'] = "Your email address has been updated.";
          respond($_SESSION['message'],true);
        } else {
          respond('Could not prepare statement!');
        }
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