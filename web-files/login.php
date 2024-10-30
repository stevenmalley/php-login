<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();

// If the user is already logged in, log them out
if (isset($_SESSION['loggedin'])) {

	session_destroy();

}

include("./login-components/options.php");
$pageTitle = $appName." Login";
include("./login-components/htmlHeader.php");

$loginID = $requireEmailAuthentication? 'email' : 'username';

?>
    <div class="accountContent">
		<div class="accountForm">
			<h1>Login</h1>
      <div class="FormResponseMessage"></div>
			<form onsubmit="authenticate(event)">
        <div>
          <label for="<?=$loginID?>">
            <i class="fas <?=$requireEmailAuthentication? 'fa-envelope' : 'fa-user'?>"></i>
          </label>
          <input type="<?=$requireEmailAuthentication? 'email' : 'text'?>" name="<?=$loginID?>"
            placeholder="<?=ucfirst($loginID)?>" id="<?=$loginID?>" required autofocus>
        </div>
        <div>
          <label for="password">
            <i class="fas fa-lock"></i>
          </label>
          <input type="password" name="password" placeholder="Password" id="password" required>
        </div>
        <div class="PasswordFooter"><a href="forgottenPassword.php">forgotten password?</a></div>
				<input type="submit" value="Login">
				<a href="./" class="cancelButton">Cancel</a>
			</form>
      <div class="FormFooter">Not yet registered? <a href="register.php">Create an account here.</a></div>
		</div>
    </div>
    <script defer>
      async function authenticate(e) {
        e.preventDefault();

        let data = {
          <?=$loginID?>:e.target["<?=$loginID?>"].value,
          password:e.target["password"].value
        };

        const response = await fetch("./login-services/index.php?p=authenticate",
          {method: "POST",
          headers: {'Accept': 'application/json'},
          body: JSON.stringify(data)
        });

        const responseText = await response.text();
        let responseJSON;
        
        try {
          responseJSON = JSON.parse(responseText);
        } catch (e) {
          console.log(e);
          console.log(responseText);
        }

        if (responseJSON) {
          if (responseJSON.result === "ok") {
            window.location.href = '<?=$loginLanding?>';
          } else {
            document.getElementsByClassName("FormResponseMessage")[0].textContent = responseJSON.message;
            document.getElementById("<?=$loginID?>").value = "";
            document.getElementById("password").value = "";
            document.getElementById("<?=$loginID?>").focus();
          }
        }
      }
    </script>

<?php

include("./login-components/htmlFooter.php");