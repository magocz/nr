<?php

function findFieldById($fieldId, $userId)
{
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
    $response = $GLOBALS['dbcon']->query($sql);
    $rows = array();
    while ($r = mysqli_fetch_assoc($response)) {
        $rows[] = $r;
    }
    return $rows;
}
