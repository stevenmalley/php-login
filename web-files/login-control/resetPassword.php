<?php

include_once("connection.php");

$_POST = json_decode(file_get_contents('php://input'), true);

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['password'], $_POST['password2']) || empty($_POST['password']) || empty($_POST['password2'])) {
	respond('Please type your new password in both boxes');
}
if (!isset($_POST['email'], $_POST['code']) || empty($_POST['email']) || empty($_POST['code'])) {
	respond('Password reset error; please visit the link in your password reset email again');
}

// We need to check if the account with that username exists.
if ($stmt = $con->prepare('SELECT id, username, password, activation_code, password_reset_code FROM accounts WHERE email = ?')) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// email found
    $stmt->bind_result($id, $username, $password, $activation_code, $password_reset_code);
    $stmt->fetch();
    
    if ($password_reset_code != $_POST['code']) {
		  respond('Error; please visit the link in your most recent password reset email. To re-send the email, click "forgotten password?" on the Login page.');
    }
    include('validatePassword.php');


    // update password
    if ($stmt = $con->prepare('UPDATE accounts SET password = ?, password_reset_code = "" WHERE email = ?')) {
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $stmt->bind_param('ss', $password, $_POST['email']);
      $stmt->execute();

      session_start();
      $_SESSION['message'] = "Your password has been reset.";
      respond($_SESSION['message'],true);
      
    }else {
      respond('Could not prepare statement');
    }

  } else {
    respond('Email address not recognised');
  }
	$stmt->close();
} else {
	respond('Could not prepare statement');
}
$con->close();


?>