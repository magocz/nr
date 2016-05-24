<?php


class FieldRepo
{

    public static function findFieldById($fieldId, $userId)
    {
        $sql = "SELECT * FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId' AND `ACTIVE` = 1 ";
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
        $sql = "SELECT * FROM FIELD WHERE `SEASON_ID` LIKE '$seasonId' AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public static function findAllFieldsBySeasonId_ReturnBE($seasonId)
    {
        $sql = "SELECT * FROM FIELD WHERE `SEASON_ID` LIKE '$seasonId' AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $operatiosn = OperationRepo::findAllDoneOperationsByFieldId($r['ID']);
            $otherCosts = OtherCostsRepo::findAllOtherCostsByFieldId($r['ID']);
            $rows[] = new FieldBE($r, $operatiosn, $otherCosts);
        }
        return $rows;
    }

    public static function deleteFieldById($fieldId, $userId)
    {
        $sql = "UPDATE FIELD SET ACTIVE = 0 WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        return $response;
    }

    public static function saveNewField($data, $userId)
    {
        $fieldNr = $data['fieldNumber'];
        $description = $data['description'];
        $plant = $data['plant'];
        $varietes = $data['varietes'];
        $tonsProHa = floatval($data['tonsProHa']);
        $plantPrice = floatval($data['plantPrice']);
        $ha = floatval($data['ha']);
        $seasonId = $_SESSION['activeSeasonId'];
        $sql = "INSERT INTO FIELD (SEASON_ID, FIELD_NR, USER_ID ,DESCRIPTION ,PLANT, VARIETES, HA, PLANT_PRICE,TONS_PRO_HA) " .
            "VALUES ('$seasonId','$fieldNr','$userId','$description','$plant','$varietes','$ha','$plantPrice','$tonsProHa')";
        $response = $GLOBALS['dbcon']->query($sql);
        echo $sql;
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
        $tonsProHa = floatval($data['tonsProHa']);
        $plantPrice = floatval($data['plantPrice']);
        $ha = floatval($data['ha']);

        $sql = "UPDATE FIELD " . "SET FIELD_NR = '$fieldNr' , DESCRIPTION = '$description',  PLANT = '$plant', VARIETES = '$varietes', HA = '$ha', " .
            "PLANT_PRICE = '$plantPrice', TONS_PRO_HA = '$tonsProHa' " .
            "WHERE ID = $fieldId AND USER_ID = $userId  AND `ACTIVE` = 1";
        echo $sql;
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function addOperationNumber($fieldId, $userId)
    {
        $sql = "UPDATE FIELD " . "SET OPERATIONS_NUMBER = OPERATIONS_NUMBER + 1 " .
            "WHERE ID = $fieldId AND USER_ID = $userId  AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function deleteOperationNumber($fieldId, $userId)
    {
        $sql = "UPDATE FIELD " . "SET OPERATIONS_NUMBER = OPERATIONS_NUMBER - 1 " .
            "WHERE ID = $fieldId AND USER_ID = $userId  AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function getFieldName($fieldId, $userId)
    {
        $sql = "SELECT * FROM FIELD WHERE `ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId' AND `ACTIVE` = 1 ";
        $response = $GLOBALS['dbcon']->query($sql);
        while ($r = mysqli_fetch_assoc($response)) {
            return 'Działka ' . $r['FIELD_NR'] . ' z dnia ';
        }
        return '';
    }
}