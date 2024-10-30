<?php

include_once('./login-control/connection.php');

$_POST = json_decode(file_get_contents('php://input'), true);

// First we check if the email and code exists...
if ($_POST['code']) {
	if ($stmt = $con->prepare('SELECT id, username, email, new_email FROM accounts WHERE activation_code = ?')) {
		$stmt->bind_param('s', $_POST['code']);
		$stmt->execute();
		// Store the result so we can check if the account exists in the database.
		$stmt->store_result();
		if ($stmt->num_rows > 0) {
      $stmt->bind_result($id, $username, $email, $new_email);
      $stmt->fetch();
			// Account exists with the requested email and code.

      // if confirming a new_email, check that there is not an account with that email address already (i.e. one registered after the request to change email address was submitted)
      if ($new_email) {
        if ($stmt = $con->prepare('SELECT id FROM accounts WHERE email = ?')) {
          $stmt->bind_param('s', $new_email);
          $stmt->execute();
          $stmt->store_result();
          if ($stmt->num_rows > 0) {
            $stmt = $con->prepare('UPDATE accounts SET new_email = "", activation_code = "" WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
          
            respond("An account has already been registered with that email address.");
            exit;
          }
        }
      } else {
        // if a logged in user is activating a new account, log them out
        session_start();
        session_destroy();
      }

			if ($stmt = $con->prepare('UPDATE accounts SET activated = TRUE, activation_code = "", email = ?, new_email = "" WHERE id = ? AND activation_code = ?')) {
				// Set the new activation code to 'activated', this is how we can check if the user has activated their account.

        // if there is a new_email, swap email for it and delete (results from changeEmail.php), otherwise preserve email value
        $email = $new_email ?: $email;

				$stmt->bind_param('sis', $email, $id, $_POST['code']);
				$stmt->execute();
				
        session_start();
        $_SESSION['message'] = $new_email ?
          "Email address updated; from now on, you must use the new email address to log in." :
          "Registration successful; your account is now activated. You may now log in.";
        respond($_SESSION['message'],true);


			}
		} else {
			respond('The account is already activated or doesn\'t exist');
		}
	} else {
    respond('Activation code not recognised');
  }
} else {
  respond('Activation code required');
}
?>