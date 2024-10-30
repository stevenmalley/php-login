<?php

include_once("connection.php");
include("../login-components/options.php");

$_POST = json_decode(file_get_contents('php://input'), true);

$loginID = $requireEmailAuthentication? 'email' : 'username';

// Now we check if the data from the login form was submitted, isset() will check if the data exists.
if (!isset($_POST[$loginID], $_POST['password']) || empty($_POST[$loginID]) || empty($_POST['password'])) {
	// Could not get the data that should have been sent.
	respond("Please fill in both the $loginID and password fields");
}

// Prepare our SQL, preparing the SQL statement will prevent SQL injection.
if ($stmt = $con->prepare("SELECT id, username, email, password, activated FROM accounts WHERE $loginID = ?")) {
	// Bind parameters (s = string, i = int, b = blob, etc), in our case the email is a string so we use "s"
  $accountID = $_POST[$loginID];
	$stmt->bind_param('s', $accountID);
	$stmt->execute();
	// Store the result so we can check if the account exists in the database.
	$stmt->store_result();

  if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $username, $email, $password, $activated);
    $stmt->fetch();
    // Account exists, now we verify the password.
    // Note: remember to use password_hash in your registration file to store the hashed passwords.
    if (password_verify($_POST['password'], $password)) {
      if ($activated) {
        // Verification success! User has logged-in!

        session_start();
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['id'] = $id;
        respond("You have successfully logged in.",true);
      } else {
        // TODO option to send another activation email
        respond('Account not yet activated. Please check your email and click the link. To re-send the activation email, click "forgotten password?" below.');
      }
    } else {
      // Incorrect password
      respond('Incorrect password');
    }
  } else {
    // Incorrect email
    respond('Account not found');
  }

	$stmt->close();
} else {
  respond('Could not prepare statement');
}


?>