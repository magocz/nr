<?php

function login($login, $password,$dbcon)
{
    $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login' AND `PASSWORD` LIKE '$password'";
    $response = $dbcon->query($sql);
    if (mysqli_num_rows($response) == 1){
        return mysqli_fetch_row($response);
    }
    return false;
}