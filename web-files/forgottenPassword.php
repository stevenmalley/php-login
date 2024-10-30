<?php
session_start();
session_destroy();

include("./login-components/options.php");
$pageTitle = $appName.": Forgotten Password";
include("./login-components/htmlHeader.php");

?>
  <div class="accountContent">
		<div class="accountForm">
			<h1>Forgotten Password</h1>
      <div class="FormResponseMessage"></div>
			<form onsubmit="forgottenPassword(event)" autocomplete="off">
				<label for="email">
					<i class="fas fa-envelope"></i>
				</label>
				<input type="email" name="email" placeholder="Email" id="email" required autofocus>
        <div class="FormFooter">
          <p>Please type your email address here. If your account is recognised and active, we will send you an email with a link so you can reset your password.</p>
          <p>If your account has not yet been activated, an activation email will be re-sent.</p>
        </div>
				<input type="submit" value="Send Email">
			</form>
      <div class="FormFooter"><a href="./">Return to the home page.</a></div>
		</div>
  </div>
  <script defer>
    async function forgottenPassword(e) {
      e.preventDefault();

      let data = {
        email:e.target["email"].value,
      };

      const response = await fetch("./login-services/index.php?p=forgottenPassword",
        {method: "POST",
        headers: {'Accept': 'application/json'},
        body: JSON.stringify(data)
      });
      
      const responseJSON = await response.json();

      if (responseJSON.result === "ok") {
        window.location.href = "notification.php";
      } else {
        document.getElementsByClassName("FormResponseMessage")[0].textContent = responseJSON.message;
      }
    }
  </script>

<?php include("./login-components/htmlFooter.php"); ?>