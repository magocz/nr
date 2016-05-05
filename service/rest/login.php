<?php

include_once "../core/authorization/login.php";
include_once "../db-config/db-config.php";

session_start();

if (isset($_POST['login'], $_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password']; // The hashed password.
    $userData = login($login, $password, $dbcon);
    if ($userData == false) {
        $_SESSION['login'] = null;
        $_SESSION['id'] = null;
        header("HTTP/1.0 401 Unauthorized");
    } else {
        $_SESSION['login'] = $login;
        $_SESSION['id'] = $userData[0];
        $_SESSION['activeSeasonId'] = $userData[6];
        header("HTTP/1.0 200 OK");
    }
} else {
      header("HTTP/1.0 405 Method Not Allowed");
      exit;
}

