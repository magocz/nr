<?php


class UserBA
{

    public function login($login, $password)
    {
        $userData = UserRepo::login($login, $password);
        if ($userData == false) {
            $_SESSION['login'] = null;
            $_SESSION['id'] = null;
            return false;
        } else {
            $_SESSION['login'] = $login;
            $_SESSION['id'] = $userData[0];
            $_SESSION['activeSeasonId'] = $userData[6];
            return true;
        }
    }

    public function logout()
    {
        print_r('Loged out');
        $_SESSION['login'] = null;
        $_SESSION['id'] = null;
        $_SESSION['activeSeasonId'] = null;
        return true;
    }

    public function register($login, $password, $mail)
    {
        $_SESSION['login'] = null;
        $_SESSION['id'] = null;
        $_SESSION['activeSeasonId'] = null;
        print_r($mail);
        print_r($password);
        print_r($login);
        if (UserRepo::isLoginFree($login)) {
            if (UserRepo::isMailFree($mail)) {
                if (UserRepo::saveUser($login, $password, $mail)) {
                    return 1;
                }
            }
            return -1;
        }
        return 0;
    }

    public function update($userData)
    {
        if (UserRepo::isLoginFree($userData['login'])) {
            if (UserRepo::isMailFree($userData['mail'])) {
                if (UserRepo::update($userData)) {
                    return 1;
                }
                return false;
            }
            return -1;
        }
        return 0;

    }

    public function findUserById()
    {
        if ($_SESSION['id'] != null) {
            return UserRepo::findUser($_SESSION['id']);
        }
        return null;
    }
}