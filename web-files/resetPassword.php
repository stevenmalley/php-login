<?php

include("./login-components/options.php");
$pageTitle = $appName.": Register User Account";
include("./login-components/htmlHeader.php");

?>
    <div class="accountContent">
		<div class="accountForm">
			<h1>Create a New Password</h1>
      <div class="FormResponseMessage"></div>
			<form onsubmit="resetPassword(event)">
				<label for="password">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password" placeholder="New Password" id="password" required>
				<label for="password2">
					<i class="fas fa-lock"></i>
				</label>
				<input type="password" name="password2" placeholder="Repeat Password" id="password2" required>
				<input type="submit" value="Reset Password">
			</form>
		</div>
    </div>
    <script defer>
      async function resetPassword(e) {
        e.preventDefault();

        let parameters = location.search.slice(1).split('&').map(arg => arg.split('='))
          .reduce((acc,keyVal) => ({[keyVal[0]]:keyVal[1],...acc}),{});

        let data = {
          email:parameters.email,
          code:parameters.code,
          password:e.target["password"].value,
          password2:e.target["password2"].value,
        };

        const response = await fetch("./login-services/index.php?p=resetPassword",
          {method: "POST",
          headers: {'Accept': 'application/json'},
          body: JSON.stringify(data)
        });
        
        const responseJSON = await response.json();

        if (responseJSON.result === "ok") {
          window.location.href = "notification.php";
        } else {
          document.querySelector(".FormResponseMessage").textContent = responseJSON.message;
          document.getElementById("password").value = "";
          document.getElementById("password2").value = "";
          document.getElementById("password").focus();
        }
      }
    </script>

<?php include("./login-components/htmlFooter.php"); ?>