<?php

function findSeasonById($seasonId)
{
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM SEASON WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}


function findSeasonsByUserId($userId)
{
    $sql = "SELECT * FROM SEASON WHERE `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}


function save($description, $startData, $stopData)
{
    $userId = $_SESSION['id'];
    $sql = "INSERT INTO SEASON (`USER_ID`,`DESCRIPTION`,`START_DATE`,`STOP_DATE`) VALUES ('$userId','$description','$startData','$stopData')";
    return $GLOBALS['dbcon']->query($sql);
}


function findAllFieldBySeasonId($seasonId)
{
    $sql = "SELECT * FROM FIELD WHERE `SEASON_ID` LIKE '$seasonId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}