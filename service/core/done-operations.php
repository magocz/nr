<?php
function findAllDoneOperationsBySeasonId($seasonId, $dbcon)
{
    $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `SEASON_ID` LIKE '$seasonId'";
    $response = $dbcon->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}

function findAllDoneOperationsByFieldId($fieldId, $dbcon)
{
    $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `FIELD_ID` LIKE '$fieldId'";
    $response = $dbcon->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}