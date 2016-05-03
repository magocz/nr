<?php

function findSeasonById($seasonId, $dbcon)
{
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM SEASON WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'";
    $response = $dbcon->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}


function findSeasonsByUserId($dbcon)
{
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM SEASON WHERE `USER_ID` LIKE '$userId'";
    $response = $dbcon->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}


function save($description, $startData, $stopData, $dbcon)
{
    $userId = $_SESSION['id'];
    $sql = "INSERT INTO SEASON (`USER_ID`,`DESCRIPTION`,`START_DATE`,`STOP_DATE`) VALUES ('$userId','$description','$startData','$stopData')";
    return $dbcon->query($sql);
}