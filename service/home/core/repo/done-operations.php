<?php


function findAllDoneOperationsBySeasonId($seasonId)
{
    $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `SEASON_ID` LIKE '$seasonId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}

function findAllDoneOperationsByFieldId($fieldId)
{
    $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `FIELD_ID` LIKE '$fieldId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}