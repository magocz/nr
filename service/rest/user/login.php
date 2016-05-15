<?php

include_once "../../business-components/user/repo/UserRepo.php";
include_once '../../../service/configs/db-config/db-config.php';
session_start();

if (isset($_POST['login'], $_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password']; // The hashed password.
    $userData = UserRepo::login($login, $password);
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

