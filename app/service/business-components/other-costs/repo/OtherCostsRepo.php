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
        $fieldId = $data['fieldId'];
        $comment = $data['comment'];
        $cost = $data['cost'];
        $date = date('Y-m-d', strtotime($data['date']));
        $sql = "INSERT INTO OTHER_COSTS (SEASON_ID, FIELD_ID, USER_ID ,COMMENT ,COST, DATE) VALUES ('$seasonId','$fieldId','$userId','$comment','$cost','$date')";
        echo $sql;
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function updateOtherCostById($data, $firstParam,$userId)
    {
        $id = $firstParam;
        $comment = $data['comment'];
        $cost = $data['cost'];

        $date = date('Y-m-d', strtotime($data['date']));
        $sql = "UPDATE OTHER_COSTS SET COMMENT = '$comment',COST = '$cost', DATE = '$date' WHERE `ID` LIKE '$id' AND `USER_ID` LIKE '$userId'";
        echo $sql;
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }

    public static function findAllOtherCostsByFieldId($fieldId, $userId)
    {
        $sql = "SELECT * FROM OTHER_COSTS WHERE `FIELD_ID` LIKE '$fieldId' AND `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }
}