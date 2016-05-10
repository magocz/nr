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

function deleteFieldById($fieldId, $userId)
{
    $sql = "DELETE FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    return $response;
}


function updateFieldById($fieldId, $data, $userId)
{
    $fieldNr = strval($data['fieldNumber']);
    $description = strval($data['description']);
    $plant = strval($data['plant']);
    $varietes = strval($data['varietes']);
    $ha = $data['ha'];
    echo $fieldNr;
    $sql = "UPDATE FIELD " . "SET FIELD_NR = '$fieldNr' , DESCRIPTION = '$description',  PLANT = '$plant', VARIETES = '$varietes', HA = '$ha' " .
        "WHERE ID = $fieldId";
    $response = $GLOBALS['dbcon']->query($sql);
    if ($response === TRUE) {
        return true;
    }
    echo "Number of rows affected: " . mysql_affected_rows() . "<br>";
}