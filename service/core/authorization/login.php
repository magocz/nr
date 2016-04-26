<?php
include_once "../../db-config/db-config.php";

session_start();

function login($login, $password,$dbcon)
{
    $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login' AND `PASSWORD` LIKE '$password'";
    $sparings = $dbcon->query($sql);
    if (mysqli_num_rows($sparings) == 1){
        return true;
    }
    return false;
}