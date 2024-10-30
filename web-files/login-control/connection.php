<?php

function respond($msg, $success = false, $data = null) {

  echo json_encode([
    "result" => $success? "ok" : "fail",
    "message" => $msg,
    "data" => $data
  ]);

  exit;
}

include_once('config.php');

// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	respond('responded to connect to MySQL: ' . mysqli_connect_error());
}