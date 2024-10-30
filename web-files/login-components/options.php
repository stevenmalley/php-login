<?php


$appName = "App Name";


// which page is the user redirected to upon logging in; default: index.php
$loginLanding = './';


// if TRUE, uses email address to log in, allows password reset via emailed link
$requireEmailAuthentication = true;


// if $invitationOnly and $requireEmailAuthentication are both TRUE, registration is restricted to those addresses provided by control/permittedEmails.php
$invitationOnly = false;