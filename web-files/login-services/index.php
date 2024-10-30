<?php

include_once('../login-control/connection.php');


if (!isset($_GET['p'])) respond("Request to service failed.");

else if (in_array($_GET['p'],
          ["register",
          "activate",
          "authenticate",
          "checkLogin",
          "logout",
          "forgottenPassword",
          "resetPassword",
          "changeUsername",
          "changeEmail",
          "changePassword"])) {
  include("../login-control/".$_GET['p'].".php");

} else respond("Sorry, this service is not available.");