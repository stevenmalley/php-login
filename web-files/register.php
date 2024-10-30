<?php

session_start();
session_destroy();


include("./login-components/options.php");
$pageTitle = $appName.": Register User Account";
include("./login-components/htmlHeader.php");

?>
    <div class="accountContent">
		<div class="accountForm">
			<h1>Register</h1>
      <div class="FormResponseMessage"></div>
			<form onsubmit="register(event)" autocomplete="off">
        <div>
          <label for="username">
            <i class="fas fa-user"></i>
          </label>
          <input type="text" name="username" placeholder="Username" id="username" required autofocus>
        </div>
        <div>
          <label for="email">
            <i class="fas fa-envelope"></i>
          </label>
          <input type="email" name="email" placeholder="Email<?=$requireEmailAuthentication? '' : ' (optional)'?>"
            id="email" <?=$requireEmailAuthentication? "required" : "" ?>>
        </div>
        <div>
          <label for="password">
            <i class="fas fa-lock"></i>
          </label>
          <input type="password" name="password" placeholder="Password" id="password" required>
        </div>
        <div>
          <label for="password2">
            <i class="fas fa-lock"></i>
          </label>
          <input type="password" name="password2" placeholder="Repeat Password" id="password2" required>
        </div>
				<input type="submit" value="Register">
        <a href="./" class="cancelButton">Cancel</a>
			</form>
      <div class="FormFooter">Already got an account? <a href="/login.php">Login here.</a></div>
		</div>
    </div>
    <script defer>
      async function register(e) {
        e.preventDefault();

        let data = {
          email:e.target["email"].value,
          username:e.target["username"].value,
          password:e.target["password"].value,
          password2:e.target["password2"].value,
        };

        const response = await fetch("./login-services/index.php?p=register",
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
            window.location.href = "notification.php";
          } else {
            document.getElementsByClassName("FormResponseMessage")[0].textContent = responseJSON.message;
          }
        }
      }
    </script>
<?php include("./login-components/htmlFooter.php"); ?>