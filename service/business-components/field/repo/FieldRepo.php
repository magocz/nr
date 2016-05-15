<?php


class FieldRepo
{

    public static function findFieldById($fieldId, $userId)
    {
        $sql = "SELECT * FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        if (count($rows) == 1) {
            $operatiosn = OperationRepo::findAllDoneOperationsByFieldId($fieldId);
            $otherCosts = OtherCostsRepo::findAllOtherCostsByFieldId($fieldId);
            return new FieldBE($rows[0], $operatiosn, $otherCosts);
        }
        return null;
    }

    public static function findAllFieldsBySeasonId($seasonId)
    {
        $sql = "SELECT * FROM FIELD WHERE `SEASON_ID` LIKE '$seasonId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public static function deleteFieldById($fieldId, $userId)
    {
        $sql = "DELETE FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        return $response;
    }

    public static function saveNewField($data, $userId)
    {
        $fieldNr = $data['fieldNumber'];
        $description = $data['description'];
        $plant = $data['plant'];
        $varietes = $data['varietes'];
        $ha = $data['ha'];
        print_r($data);
        //$seasonId = strval($data['seasonId']) == '-1' ? $_SESSION['activeSeasonId'] : $data['seasonId'];
        $seasonId = 1;
        $sql = "INSERT INTO FIELD (SEASON_ID, FIELD_NR, USER_ID ,DESCRIPTION ,PLANT, VARIETES, HA) VALUES ('$seasonId','$fieldNr','$userId','$description','$plant',' $varietes','$ha')";
        $response = $GLOBALS['dbcon']->query($sql);
        echo ' dsdsds';
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function updateFieldById($data, $userId)
    {
        $fieldId = $data['id'];
        $fieldNr = $data['fieldNumber'];
        $description = $data['description'];
        $plant = $data['plant'];
        $varietes = $data['varietes'];
        $ha = $data['ha'];

        $sql = "UPDATE FIELD " . "SET FIELD_NR = '$fieldNr' , DESCRIPTION = '$description',  PLANT = '$plant', VARIETES = '$varietes', HA = '$ha' " .
            "WHERE ID = $fieldId AND USER_ID = $userId";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }
}