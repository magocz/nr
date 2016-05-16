<?php

include_once "../../business-components/user/repo/UserRepo.php";
include_once '../../../service/configs/db-config/db-config.php';

session_start();
$method = $_SERVER['REQUEST_METHOD'];

if ($_SESSION['login'] == null) {
    header('HTTP/1.0 403 Forbidden');
    exit;
} else {
    if ($method == 'GET') {
        $_SESSION['login'] = null;
        $_SESSION['id'] = mull;
        $_SESSION['activeSeasonId'] = null;
        header("HTTP/1.0 200 OK");
        exit;
    } else {
        header("HTTP/1.0 500 Logout Error");
        exit;
    }
}

