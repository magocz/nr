<?php


class SeasonRepo
{
    public static function findSeasonById($seasonId, $userId)
    {
        $sql = "SELECT * FROM SEASON WHERE `ID` LIKE '$seasonId' AND `USER_ID` LIKE '$userId'";
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


    public static function findAllUserSeasons($userId)
    {
        $sql = "SELECT * FROM SEASON WHERE `USER_ID` LIKE '$userId'";
        $response = $GLOBALS['dbcon']->query($sql);
        $rows = array();
        while ($r = mysqli_fetch_assoc($response)) {
            $rows[] = $r;
        }
        return $rows;
    }


    public static function saveSeason($description)
    {
        $userId = $_SESSION['id'];
        $sql = "INSERT INTO SEASON (`USER_ID`,`DESCRIPTION`) VALUES ('$userId','$description')";
        if ($GLOBALS['dbcon']->query($sql) == TRUE) {
            return true;
        }
        return false;
    }

}