<?php

class OperationRepo
{
    public static function findAllDoneOperationsBySeasonId($seasonId)
    {
        $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `SEASON_ID` LIKE '$seasonId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            if (key_exists($r['FIELD_ID'], $rows)) {
                array_push($rows[$r['FIELD_ID']], $r);
            } else {
                @$rows[$r['FIELD_ID']] = array($r);
            }
        }
        return $rows;
    }

    public static function findAllDoneOperationsByFieldId($fieldId)
    {
        $sql = "SELECT * FROM DONE_OPERATION_DETAILS WHERE `FIELD_ID` LIKE '$fieldId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }

        return $rows;
    }

    public static function saveNewOperation($operationData, $seasonId, $userId)
    {
        $fieldId = $operationData['fieldId'];
        $operationDate = $operationData['date'];
        $meansName = $operationData['meansName'];
        $meansType = $operationData['meansType'];
        $meansDoseProKgProHa = floatval(@$operationData['meansDoseInLProHa']);
        $meansDoseProLProHa = @$operationData['meansDoseInKgProHa'] == '' ? 0 : $operationData['meansDoseInKgProHa'];
        $cause = @$operationData['cause'];
        $economicHarm = floatval($operationData['economicHarm']);
        $costProHa = floatval($operationData['costProHa']);
        $comment = @$operationData['comment'];
        print_r($operationData);
        $sql = "INSERT INTO DONE_OPERATION_DETAILS (`FIELD_ID`,`USER_ID`,`SEASON_ID`,`DATE`,`MEANS_NAME`,`MEANS_TYPE`,`MEANS_DOSE_L_HA`,`MEANS_DOSE_KG_HA`,`CAUSE`,`ECONOMIC_HARN`, `COST_PRO_HA`,`COMMENT`)"
            . " VALUES ('$fieldId', '$userId','$seasonId','$operationDate','$meansName','$meansType','$meansDoseProKgProHa','$meansDoseProLProHa','$cause','$economicHarm','$costProHa','$comment')";
        $response = $GLOBALS['dbcon']->query($sql);
        if ($response === TRUE) {
            return true;
        }
        return false;
    }
}
