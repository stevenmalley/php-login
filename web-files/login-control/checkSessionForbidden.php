<?php

$path_to_home = '';
while (!file_exists($path_to_home.'.login_path_anchor')) $path_to_home .= '../';
$path_to_home = $path_to_home ?: './';

if (!$loggedin) {


	?>
      <div>You do not have access to this page. Please <a href="<?=$path_to_home?>login.php">log in here</a>.</div>
  <?php

  require($path_to_home."login-components/htmlFooter.php");
  exit;
}
