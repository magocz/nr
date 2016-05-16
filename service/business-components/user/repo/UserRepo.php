<?php

class UserRepo
{
    public static function login($login, $password)
    {
        $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login' AND `PASSWORD` LIKE '$password'";
        $result = $GLOBALS['dbcon']->query($sql);
        if (mysqli_num_rows($result) == 1) {
            return mysqli_fetch_row($result);
        }
        return false;
    }

    public static function isLoginFree($login)
    {
        $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login'";
        $result = $GLOBALS['dbcon']->query($sql);
        if (mysqli_num_rows($result) == 1) {
            return false;
        }
        return true;
    }

    public static function isMailFree($mail)
    {
        $sql = "SELECT * FROM USER WHERE `MAIL` LIKE '$mail'";
        $result = $GLOBALS['dbcon']->query($sql);
        if (mysqli_num_rows($result) == 1) {
            return false;
        }
        return true;
    }

    public static function registration($login, $password, $mail)
    {
        if (!isLoginFree($login)) {
            return 0;
        }
        if (!isMailFree($mail)) {
            return -1;
        }

        $sql = "INSERT INTO USER (`LOGIN`, `PASSWORD`,`MAIL`) VALUES ('$login','$password','$mail')";
        $GLOBALS['dbcon']->query($sql);
        return 1;

    }

    public static function changeActiveSeason($seasonId, $userId)
    {
        $sql = "UPDATE USER SET selected_season_id = '$seasonId' WHERE id = '$userId'";
        print_r($sql);
        if ($GLOBALS['dbcon']->query($sql) == TRUE) {
            return true;
        }
        return false;
    }

}

