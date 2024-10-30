let logoutLink = document.querySelector(".logoutLink");
if (logoutLink) logoutLink.onclick = e => {
  fetch("../login-services/index.php?p=logout");
}