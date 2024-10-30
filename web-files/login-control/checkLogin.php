<?php
// We need to use sessions, so you should always start sessions using the below code.
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


// check expiry date, update if not expired




// If the user is not logged in redirect to the home page...
if (isset($_SESSION['loggedin'])) {

  respond("Logged in",true,
    ["username" => $_SESSION["name"],
     "email" => $_SESSION["email"]]);

} else {

  respond("You are not logged in");

}
