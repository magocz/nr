<?php

include_once "../core/authorization/registration.php";
include_once "../core/authorization/login.php";
include_once "../db-config/db-config.php";

session_start();

if (isset($_POST['login'], $_POST['password'],$_POST['mail'])) {
    $login = $_POST['login'];
    $password = $_POST['password']; // The hashed password.
    $mail = $_POST['mail'];
    $response = registration($login, $password, $mail, $dbcon) ;
    if ($response == 1) {
      $userData = login($login, $password, $dbcon);
       $_SESSION['login'] = $login;
              $_SESSION['id'] = $userData[0];
         header("HTTP/1.0 201 Created");

    } else if($response == -1) {
           $_SESSION['login'] = null;
                $_SESSION['id'] = null;
         header("HTTP/1.0 401 MailError");
    }
     else if($response == 0) {
               $_SESSION['login'] = null;
                    $_SESSION['id'] = null;
             header("HTTP/1.0 401 LoginError");
        }
} else {
      header("HTTP/1.0 405 Method Not Allowed");
      exit;
}

