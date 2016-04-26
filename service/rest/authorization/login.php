<?php
include_once "../../db-config/db-config.php";

function login($login, $password,$dbconn){
    $sqlQuery = "SELECT id FROM USER";
    $result = $dbconn->query($sqlQuery);
    echo count($result);
}

echo login("liweye","dsds",$dbconn);