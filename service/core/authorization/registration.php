<?php

    function isLoginFree($login,$dbcon)
    {
        $sql = "SELECT * FROM USER WHERE `LOGIN` LIKE '$login'";
        $result = $dbcon->query($sql);
        if (mysqli_num_rows($result) == 1){
            return false;
        }
        return true;
    }

 function isMailFree($mail,$dbcon)
    {
        $sql = "SELECT * FROM USER WHERE `MAIL` LIKE '$mail'";
        $result = $dbcon->query($sql);
        if (mysqli_num_rows($result) == 1){
            return false;
        }
        return true;
    }
    function registration($login,$password,$mail, $dbcon){
     if(!isLoginFree($login,$dbcon)){
        return 0;
     }
      if(!isMailFree($mail,$dbcon)){
             return -1;
          }

            $sql = "INSERT INTO USER (`LOGIN`, `PASSWORD`,`MAIL`) VALUES ('$login','$password','$mail')";
            $dbcon->query($sql);
            return 1;

    }