<?php

function findFieldById($fieldId, $userId)
{
    $sql = "SELECT * FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
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

function deleteFieldById($fieldId, $userId)
{
    $sql = "DELETE FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    return $response;
}

function saveNewField($data, $seasonId, $userId)
{
    $fieldNr = iconv("UTF-8", "ISO-8859-2", $data['fieldNumber']);
    $description = iconv("UTF-8", "ISO-8859-2", $data['description']);
    $plant = iconv("UTF-8", "ISO-8859-2", $data['plant']);
    $varietes = iconv("UTF-8", "ISO-8859-2", $data['varietes']);
    $ha = $data['ha'];
    $sql = "INSERT INTO FIELD (SEASON_ID, FIELD_NR, USER_ID ,DESCRIPTION ,PLANT, VARIETES, HA) VALUES ('$seasonId ','$fieldNr ',' $userId ',' $description ',' $plant ',' $varietes ','$ha ')";
    $response = $GLOBALS['dbcon']->query($sql);
    if ($response === TRUE) {
        return true;
    }
    echo $response;
    return false;
}

function updateFieldById($fieldId, $data, $userId)
{
    $fieldNr = iconv("UTF-8", "ISO-8859-2", $data['fieldNumber']);
    $description = iconv("UTF-8", "ISO-8859-2", $data['description']);
    $plant = iconv("UTF-8", "ISO-8859-2", $data['plant']);
    $varietes = iconv("UTF-8", "ISO-8859-2", $data['varietes']);
    $ha = $data['ha'];
    $sql = "UPDATE FIELD " . "SET FIELD_NR = '$fieldNr' , DESCRIPTION = '$description',  PLANT = '$plant', VARIETES = '$varietes', HA = '$ha' " .
        "WHERE ID = $fieldId AND USER_ID = $userId";
    $response = $GLOBALS['dbcon']->query($sql);
    if ($response === TRUE) {
        return true;
    }
    return false;
}