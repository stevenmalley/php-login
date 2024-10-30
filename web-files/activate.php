<?php
session_start();
session_destroy();

include("./login-components/options.php");
$pageTitle = $appName.": Activate Account";
include("./login-components/htmlHeader.php");

?>
  <div class="accountContent">
		<div class="accountForm">
      <div class="FormResponseMessage">Activating account...</div>
      <div class="FormFooter"><a href="./">Return to the home page.</a></div>
		</div>
  </div>
  <script defer>
    async function activateAccount() {
      let parameters = location.search.slice(1).split('&').map(arg => arg.split('='))
        .reduce((acc,keyVal) => ({[keyVal[0]]:keyVal[1],...acc}),{});

      const response = await fetch("./login-services/index.php?p=activate",
        {method: "POST",
        headers: {'Accept': 'application/json'},
        body: JSON.stringify({code:parameters.code})
      });
      
      const responseJSON = await response.json();

      if (responseJSON.result === "ok") {
        window.location.href = "notification.php";
      } else {
        document.querySelector(".FormResponseMessage").textContent = responseJSON.message;
      }
    }
    activateAccount();
  </script>

<?php include("./login-components/htmlFooter.php"); ?>