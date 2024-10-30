<?php


if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 8) {
  respond('Password must be between 8 and 20 characters long');
}
if ($_POST['password'] != $_POST['password2']) {
  respond('Passwords do not match');
}