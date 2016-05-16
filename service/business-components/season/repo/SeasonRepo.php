<?php


class SeasonRepo
{
    public static function findSeasonById($seasonId, $userId)
    {
        $sql = "SELECT * FROM SEASON WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'  AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        $season = null;
        if (count($response) == 1) {
            while ($r = mysqli_fetch_assoc($response)) {
                $season = new SeasonBE($r);
                $fields = FieldRepo::findAllFieldsBySeasonId($seasonId);
                $operatiosn = OperationRepo::findAllDoneOperationsBySeasonId($seasonId);
                $otherCosts = OtherCostsRepo::findAllOtherCostsBySeasonId($seasonId);
                foreach ($fields as $field) {
                    $fieldOperations = key_exists($field['ID'], $operatiosn) ? $operatiosn[$field['ID']] : array();
                    $fieldOtherCosts = key_exists($field['ID'], $otherCosts) ? $otherCosts[$field['ID']] : array();
                    $season->addField(new FieldBE($field, $fieldOperations, $fieldOtherCosts));
                }
            }
        }
        return $season;
    }

    public static function isThtatUserSeason($seasonId, $userId)
    {
        $sql = "SELECT * FROM SEASON WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'  AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        if (count($response) == 1) {
            return tru;
        }
        return false;
    }


    public static function findAllUserSeasons($userId)
    {
        $sql = "SELECT * FROM SEASON WHERE `USER_ID` LIKE '$userId'  AND `ACTIVE` = 1";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $seasonBF = new SeasonBE($r);
            if ($seasonBF->id == $_SESSION['activeSeasonId']) {
                $seasonBF->active = true;
            }
            $rows[] = $seasonBF;
        }
        return $rows;
    }

    public static function deleteSeason($seasonId, $userId)
    {
        $sql = "UPDATE SEASON SET ACTIVE = 0 WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'";
        if ($GLOBALS['dbcon']->query($sql) === TRUE) {
            return true;
        }
        return false;
    }


    public static function saveSeason($name, $userId)
    {
        $sql = "INSERT INTO SEASON (`USER_ID`,`NAME`) VALUES ('$userId','$name')";
        print_r($sql);
        if ($GLOBALS['dbcon']->query($sql) === TRUE) {
            return $GLOBALS['dbcon']->insert_id;
        }

        return false;
    }

}