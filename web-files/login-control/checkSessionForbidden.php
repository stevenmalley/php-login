<?php
// We need to use sessions, so you should always start sessions using the below code.
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}


// check expiry date, update if not expired




// If the user is not logged in redirect to the home page...
if (!isset($_SESSION['loggedin'])) {

  $path_to_origin = '';
  while (!file_exists($path_to_origin.'.login_path_anchor')) $path_to_origin .= '../';
  $path_to_origin = $path_to_origin ?: './';


	?>
      <div>You do not have access to this page. Please <a href="<?=$path_to_origin?>login.php">log in here</a>.</div>
  <?php

  include($path_to_origin."login-components/htmlFooter.php");
  exit;
}
