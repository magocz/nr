<?php


class  OtherCostsRepo
{

    public static function findOtherCostById($otherCostID, $userId)
    {
        $sql = "SELECT * FROM OTHER_COSTS WHERE `ID` LIKE '$otherCostID' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }

    public static function findAllOtherCostsBySeasonId($seasonId)
    {
        $sql = "SELECT * FROM OTHER_COSTS WHERE `SEASON_ID` LIKE '$seasonId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            if (key_exists($r['FIELD_ID'], $rows)) {
                array_push($rows[$r['FIELD_ID']], $r);
            } else {
                array_push($rows, array($r));
            }
        }
        return $rows;
    }

    public static function deleteOtherCostById($otherCostID, $userId)
    {
        $sql = "DELETE FROM OTHER_COSTS WHERE `ID` LIKE '$otherCostID' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        return $response;
    }

    public static function saveNewOtherCost($data, $seasonId, $userId)
    {
        $fieldNr = iconv("UTF-8", "ISO-8859-2", $data['fieldNumber']);
        $description = iconv("UTF-8", "ISO-8859-2", $data['description']);
        $plant = iconv("UTF-8", "ISO-8859-2", $data['plant']);
        $varietes = iconv("UTF-8", "ISO-8859-2", $data['varietes']);
        $ha = $data['ha'];
        $sql = "INSERT INTO OTHER_COSTS (SEASON_ID, FIELD_NR, USER_ID ,DESCRIPTION ,PLANT, VARIETES, HA) VALUES ('$seasonId ','$fieldNr ',' $userId ',' $description ',' $plant ',' $varietes ','$ha ')";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        echo $response;
        return false;
    }

    public static function updateOtherCostById($otherCostID, $data, $userId)
    {
        $fieldNr = iconv("UTF-8", "ISO-8859-2", $data['fieldNumber']);
        $description = iconv("UTF-8", "ISO-8859-2", $data['description']);
        $plant = iconv("UTF-8", "ISO-8859-2", $data['plant']);
        $varietes = iconv("UTF-8", "ISO-8859-2", $data['varietes']);
        $ha = $data['ha'];
        $sql = "UPDATE OTHER_COSTS " . "SET FIELD_NR = '$fieldNr' , DESCRIPTION = '$description',  PLANT = '$plant', VARIETES = '$varietes', HA = '$ha' " .
            "WHERE ID = $fieldId AND USER_ID = $userId";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function findAllOtherCostsByFieldId($fieldId)
    {
        $sql = "SELECT * FROM OTHER_COSTS WHERE `FIELD_ID` LIKE '$fieldId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }
}