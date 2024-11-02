FUNCTIONALITY

user account creation
login
logout
optional email authentication (accounts must be activated by clicking an emailed link)
usernames can be changed
email addresses can be changed; if email authentication is being used, an email with a confirmation link will be sent
passwords can be changed
if an account has an associated email address, forgotten passwords can be reset via an email with a 'reset password' link

usernames may be up to 30 characters, all alphanumeric
passwords must be 8-20 characters long

a site involving any number of pages and nested subdirectories can be created and integrated within the login system's file structure, or the login system can be used as an API for a separate application.

persistent sessions:
upon logging in, the session cookie ID is saved to the account's DB entry, and the date is saved.
when a user with a cookie with matching ID visits the site, if they are within a certain time of the last login, they are automatically logged in.



HOW TO USE

Set up DB using docs/sql/db.sql.
Create login-control/config.php with DB credentials following the pattern of login-control/config-EXAMPLE.php.
  $homePath must be the full path to the application's root directory

Update login-components/options.php with:
- the name of the app
- the path to the $loginLanding, the page a user is redirected to when they log in, eg. './' or './profile.php'
- true/false for $requireEmailAuthentication
- $invitationOnly, if using $requireEmailAuthentication, restricts registration to those provided by control/permittedEmails.php

Include a favicon.ico, logo192.png, logo512.png in the root directory.

Upload everything except docs/



USING AS A PHP APPLICATION

Modify index.php, profile.php and create new pages in the root directory.
Any other pages that require the top navigation bar with links to user account functionality must begin by including:
  include("./login-components/options.php");
  $pageTitle = $appName." PAGE TITLE";
  include("./login-components/htmlHeader.php");
and end with:
  <?php include("./login-components/htmlFooter.php"); ?>
For subdirectories, replace the initial "./" of the include() paths with appropriate paths as required.

To include stylesheet links and scripts, add the arrays $styles and $scripts before the "htmlHeader" include().
$styles must be an array of path strings: eg. $styles = ["css/path_to_file.css","..."];
$scripts must be an array of objects, each of which have the "path" value and an "options" value; "options" consists of an array containing any attributes to be added to the script tag (any of "async" and "defer"): eg. [["path" => "js/path_to_file.js", "options" => ["async","defer"]], [...]];
Paths must be relative to the home directory (ie. beginning with "js/" or "css/").

$_SESSION['id'], $_SESSION['username'], $_SESSION['email'] and $_SESSION['loggedin'] are available in files with the htmlHeader.
JS constants 'loggedin' (Boolean) and 'username' (String or null) are available in files with the htmlHeader.

for pages with variable content that depends upon whether the user is logged in or not:
  <?php if (isset($_SESSION['loggedin'])) { ?>
    <Code to show logged in users>
  <?php } else { ?>
    <Code to show non-logged-in users>
  <?php } ?>

for pages that are inaccessible to non-logged-in users (redirect to the home page), begin with:
  include("./login-control/checkSessionRedirect.php");

for pages that are inaccessible to non-logged-in users ('forbidden' message), add this line after 'include("./login-components/htmlHeader.php");':
  include("./login-control/checkSessionForbidden.php");



USING AS AN API







STRUCTURE

root: available webpages for both login functionality and app
components/: webpage sections and data common to multiple pages
services/: API endpoint for sending data to login system
control/: server-side account functionality