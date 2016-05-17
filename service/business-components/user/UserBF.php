<?php


class UserBF
{
    private $userBA;

    public function __construct()
    {
        $this->userBA = new UserBA();
    }

    public function login($login, $password)
    {
        return $this->userBA->login($login, $password);
    }

    public function logout()
    {
        return $this->userBA->logout();
    }

    public function register($login, $mail, $password)
    {
        return $this->userBA->register($login, $password, $mail);
    }

    public function update($userData)
    {
        return $this->userBA->update($userData);
    }

    public function findUser()
    {
        return json_encode($this->userBA->findUserById());
    }

}