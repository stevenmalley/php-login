<?php
include_once("./login-control/connection.php");

include("./login-components/options.php");
$pageTitle = $appName.": user profile";
include('./login-components/htmlHeader.php');
include_once('./login-control/checkSessionForbidden.php');
?>
    <div class="ChangeDataModal">
      <div class="changeDataModalWindow ChangeDataUsername">
        <h2>Change Username</h2>
        <form class="ChangeDataUsernameForm">
          <div class="form-floating">
            <input type="text" name="username" id="username" class="form-control" placeholder="New username" required />
            <label for="username">New username</label>
          </div>
          <div>
            <input type="submit" value="Submit" />
            <input type="button" class="ChangeDataUsernameCancel" value="Cancel" />
          </div>
        </form>
        <div class="changeDataResponse ChangeDataUsernameResponse"></div>
      </div>
      <div class="changeDataModalWindow ChangeDataEmail">
        <h2>Update Email Address</h2>
        <form class="ChangeDataEmailForm">
          <div class="form-floating">
            <input type="email" name="email" id="email" class="form-control" placeholder="New email address" required />
            <label for="email">New email address</label>
          </div>
          <div>
            <input type="submit" value="Submit" />
            <input type="button" class="ChangeDataEmailCancel" value="Cancel" />
          </div>
        </form>
        <div class="changeDataResponse ChangeDataEmailResponse"></div>
      </div>
      <div class="changeDataModalWindow ChangeDataPassword">
        <h2>Change Password</h2>
        <form class="ChangeDataPasswordForm">
          <div class="form-floating">
            <input type="password" name="old-password" id="old-password" class="form-control" placeholder="Type old password" required />
            <label for="old-password">Type old password</label>
          </div>
          <div class="form-floating">
            <input type="password" name="new-password-1" id="new-password-1" class="form-control" placeholder="New password" required />
            <label for="new-password-1">New password</label>
          </div>
          <div class="form-floating">
            <input type="password" name="new-password-2" id="new-password-2" class="form-control" placeholder="Repeat new password" required />
            <label for="new-password-2">Repeat new password</label>
          </div>
          <div>
            <input type="submit" value="Submit" />
            <input type="button" class="ChangeDataPasswordCancel" value="Cancel" />
          </div>
        </form>
        <div class="changeDataResponse ChangeDataPasswordResponse"></div>
      </div>
    </div>
    <div class="accountContent">
			<h2>Profile</h2>
			<div>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=htmlspecialchars($_SESSION['username'], ENT_QUOTES)?>
              <button class="EditUsername">
                <i class="fa-solid fa-pencil"></i>
              </button>
            </td>
					</tr>
          <tr>
            <td>Email:</td>
            <td><?=htmlspecialchars($_SESSION['email'], ENT_QUOTES)?>
            <button class="EditEmail">
              <i class="fa-solid fa-pencil"></i>
            </button>
          </td>
          </tr>
          <tr>
            <td></td>
            <td>
              <button class="EditPassword">Change password</button>
            </td>
          </tr>
				</table>

        <a href="./" class="accountButton">OK</a>
			</div>
    </div>
    <script defer>
      document.querySelector(".ChangeDataModal").onclick = e => {
        if (e.target.classList.contains("ChangeDataModal")) {
          document.querySelector(".changeDataModalWindow.displayModal").classList.remove("displayModal");
          document.querySelector(".ChangeDataModal").classList.remove("displayModal");
        }
      };
      ["Username","Email","Password"].forEach(attr => {
        document.querySelector(`.Edit${attr}`).onclick = e => {
          document.querySelector(`.ChangeData${attr}`).classList.add("displayModal");
          document.querySelector(`.ChangeDataModal`).classList.add("displayModal");
          document.querySelector(`.ChangeData${attr} input`).focus();
        };
        document.querySelector(`.ChangeData${attr}Cancel`).onclick = e => {
          document.querySelector(`.ChangeData${attr}`).classList.remove("displayModal");
          document.querySelector(`.ChangeDataModal`).classList.remove("displayModal");
        };
      });
      
      document.querySelector(".ChangeDataUsernameForm").onsubmit = async e => {
        e.preventDefault();
        document.querySelector(".ChangeDataUsernameResponse").textContent = "submitting...";
        
        const response = await fetch("./login-services/index.php?p=changeUsername",
          {method: "POST",
          headers: {'Accept': 'application/json'},
          body: JSON.stringify({username:e.target["username"].value})
        });
        
        const responseText = await response.text();
        let responseJSON;
        
        try {
          responseJSON = JSON.parse(responseText);
        } catch (e) {
          console.log(e);
          console.log(responseText);
        }

        if (responseJSON && responseJSON.result === "ok") {
          window.location.href = "notification.php";
        } else {
          document.querySelector(".ChangeDataUsernameResponse").textContent = responseJSON.message;
        }
      };

      document.querySelector(".ChangeDataEmailForm").onsubmit = async e => {
        e.preventDefault();
        document.querySelector(".ChangeDataEmailResponse").textContent = "submitting...";
        
        const response = await fetch("./login-services/index.php?p=changeEmail",
          {method: "POST",
          headers: {'Accept': 'application/json'},
          body: JSON.stringify({email:e.target["email"].value})
        });
        
        const responseText = await response.text();
        let responseJSON;
        
        try {
          responseJSON = JSON.parse(responseText);
        } catch (e) {
          console.log(e);
          console.log(responseText);
        }

        if (responseJSON && responseJSON.result === "ok") {
          window.location.href = "notification.php";
        } else {
          document.querySelector(".ChangeDataEmailResponse").textContent = responseJSON.message;
        }
      };

      document.querySelector(".ChangeDataPasswordForm").onsubmit = async e => {
        e.preventDefault();
        document.querySelector(".ChangeDataPasswordResponse").textContent = "submitting...";
        
        const response = await fetch("./login-services/index.php?p=changePassword",
          {method: "POST",
          headers: {'Accept': 'application/json'},
          body: JSON.stringify({"old-password":e.target["old-password"].value,
                                password:e.target["new-password-1"].value,
                                password2:e.target["new-password-2"].value})
        });
        
        const responseText = await response.text();
        let responseJSON;
        
        try {
          responseJSON = JSON.parse(responseText);
        } catch (e) {
          console.log(e);
          console.log(responseText);
        }

        if (responseJSON && responseJSON.result === "ok") {
          window.location.href = "notification.php";
        } else {
          document.querySelector(".ChangeDataPasswordResponse").textContent = responseJSON.message;
        }
      };

    </script>
    <?php include('./login-components/htmlFooter.php') ?>