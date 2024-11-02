let logoutLink = document.querySelector(".logoutLink");
if (logoutLink) logoutLink.onclick = e => {
  document.cookie = "PHPSESSID=;path=/;Max-Age=0";
  fetch(e.target.href+"login-services/index.php?p=logout");
}