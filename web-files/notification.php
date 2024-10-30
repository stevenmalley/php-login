<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['message']) || !$_SESSION['message']) {
  header("Location: ./");
}

include("./login-components/options.php");
$pageTitle = $appName.": notification";
include('./login-components/htmlHeader.php');
?>
    <div class="accountContent">
			<div id="userMessage">
        <p><?php echo $_SESSION['message'] ?></p>
        <a href="./" class="accountButton">OK</a>
        <?php
          if (!isset($_SESSION['loggedin'])) {
            echo '<a href="./login.php" class="accountButton">Login</a>';
          }
        ?>
      </div>
    </div>
<?php

include('./login-components/htmlFooter.php');

$_SESSION['message'] = null;

?>