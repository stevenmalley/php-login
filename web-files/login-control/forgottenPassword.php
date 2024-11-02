<?php

include_once("connection.php");

$_POST = json_decode(file_get_contents('php://input'), true);

if (!isset($_POST['email']) || empty($_POST['email'])) {
	respond('Please submit your email address');
}

if ($stmt = $con->prepare('SELECT id, activated, activation_code FROM accounts WHERE email = ?')) {
	$stmt->bind_param('s', $_POST['email']);
	$stmt->execute();
	$stmt->store_result();
	// Store the result so we can check if the account exists in the database.
	if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $activated, $activation_code);
    $stmt->fetch();

    if ($activated) {
      // account has been activated
      // create password_reset_code
      // send email with code and link to resetPassword.php
      if ($stmt = $con->prepare('UPDATE accounts SET password_reset_code = ? WHERE email = ?')) {

        function randomHexString($length) {
          $str = "";
          while ($length-- > 0) $str .= dechex(rand(0,15));
          return $str;
        }

        $uniqid = uniqid().randomHexString(10);
        $email = $_POST['email'];
        $stmt->bind_param('ss', $uniqid, $email);
        $stmt->execute();

        include_once('config.php');
        include("../login-components/options.php");

        $from    = 'noreply@'.$emailServer;
        $subject = $appName.': reset your password';
        $headers = 'From: ' . $from . "\r\n" . 'Reply-To: ' . $from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        
        $reset_password_link = $homePath.'/resetPassword.php?email=' . $_POST['email'] . '&code=' . $uniqid;
        $message = '<p>Please click the following link to reset your '.$appName.' password: <a href="' . $reset_password_link . '">' . $reset_password_link . '</a></p><p>Ignore this email if you did not reset your '.$appName.' password.</p>';
        mail($_POST['email'], $subject, $message, $headers);

      } else {
        respond('Could not prepare statement!');
      }
    } else {
      // unactivated account. resend activation email

      include('activationEmail.php'); // requires $email
    }
  }

  $stmt->close();
  // whether the email address was recognised or not, redirect the user

  session_start();
  $_SESSION['message'] = "If your email address is recognised, we will send you an email. Please open it and click the link to reset your password.";
  respond($_SESSION['message'],true);

} else {
  respond('Could not prepare statement!');
}
    

$con->close();


?>