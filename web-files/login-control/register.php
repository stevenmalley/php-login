<?php


include_once("connection.php");
include("../login-components/options.php");

$_POST = json_decode(file_get_contents('php://input'), true);

// Now we check if the data was submitted, isset() function will check if the data exists.
if (!isset($_POST['username'], $_POST['password'], $_POST['password2']) && (!$requireEmailAuthentication || isset($_POST['email']))) {
	// Could not get the data that should have been sent.
	respond('Please complete the registration form');
}
// Make sure the submitted registration values are not empty.
if (empty($_POST['username']) || empty($_POST['password']) || empty($_POST['password2']) || ($requireEmailAuthentication && empty($_POST['email']))) {
	// One or more values are empty.
	respond('Please complete the registration form');
}

if ($requireEmailAuthentication && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
  respond('Email is not valid');
}
include('validateUsername.php');
include('validatePassword.php');

$validEmail = (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))? $_POST['email'] : '';

// We need to check if the account with that username exists.
$accountSearchQuery = 'SELECT id, username, email, password, activation_code FROM accounts WHERE username = ?';
if ($requireEmailAuthentication || $validEmail) $accountSearchQuery .= ' OR email = ?';
if ($stmt = $con->prepare($accountSearchQuery)) {
	// Bind parameters (s = string, i = int, b = blob, etc), hash the password using the PHP password_hash function.
	if ($validEmail) $stmt->bind_param('ss', $_POST['username'], $_POST['email']);
  else $stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
		// Username or email already exists
    $stmt->bind_result($id, $username, $email, $password, $activation_code);
    $stmt->fetch();
    if ($username == $_POST['username']) {
		  respond('Username exists, please choose another');
    } else if ($validEmail && $email == $_POST['email']) {
      respond('That email is associated with an account already');
    } else {
      respond('Matching account found'); // should not be possible: either username or email must match
    }
	} else {

    // registration by invitation only
    if ($requireEmailAuthentication && $invitationOnly) {
      include('permittedEmails.php');
      if (!in_array($_POST['email'], $permittedEmails)) {
        respond('This app is currently invitation only. Please try again later.');
      }
    }

    // Username and email doesn't exist, insert new account
    if ($stmt = $con->prepare('INSERT INTO accounts (username, password, email, activated, activation_code) VALUES (?, ?, ?, ?, ?)')) {

      function randomHexString($length) {
        $str = "";
        while ($length-- > 0) $str .= dechex(rand(0,15));
        return $str;
      }

      // We do not want to expose passwords in our database, so hash the password and use password_verify when a user logs in.
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $activation_code = $requireEmailAuthentication? uniqid().randomHexString(10) : "";
      $activated = $requireEmailAuthentication ? 0 : 1;
      $stmt->bind_param('sssis', $_POST['username'], $password, $validEmail, $activated, $activation_code);
      $stmt->execute();
      
      if ($requireEmailAuthentication) {
        include('activationEmail.php');
        session_start();
        $_SESSION['message'] = "You should receive an email shortly. Please click the link in it to activate your account.";
        respond($_SESSION['message'],true);
      } else {
        session_start();
        $_SESSION['message'] = "Your account has been created. You may now log in.";
        respond($_SESSION['message'],true);
      }

    } else {
      // Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all three fields.
      respond('Could not prepare statement!');
    }
	}
	$stmt->close();
} else {
	// Something is wrong with the SQL statement, so you must check to make sure your accounts table exists with all 3 fields.
	respond('Could not prepare statement!');
}
$con->close();


?>