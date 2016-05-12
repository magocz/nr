<?php

function findSeasonById_DB($seasonId)
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


function findAllUserSeasons_DB($userId)
{
    $sql = "SELECT * FROM SEASON WHERE `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}


function saveSeason_DB($description)
{
    $userId = $_SESSION['id'];
    $sql = "INSERT INTO SEASON (`USER_ID`,`DESCRIPTION`) VALUES ('$userId','$description')";
    if ($GLOBALS['dbcon']->query($sql) == TRUE) {
        return true;
    }
    return false;
}
