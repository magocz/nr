<?php

function login($login, $password,$dbcon)
{
    $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login' AND `PASSWORD` LIKE '$password'";
    $result = $dbcon->query($sql);
    if (mysqli_num_rows($result) == 1){
        return mysqli_fetch_row($result);
    }
    return false;
}