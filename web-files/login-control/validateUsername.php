<?php


if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['username']) == 0 || strlen($_POST['username']) > 30) {
  respond('Username is not valid. It must be no more than 30 alphanumeric characters.');
}