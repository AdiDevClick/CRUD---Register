<?php

class LoginView extends LoginController
{
    /* public function showUsers($pwd, $email)
    {
        $this->login($pwd, $email);
    }

    public function showRecipes()
    {
        $result = $this->getRecipes();
        return $result;
    }   */
    public function displayRecipes(): array
    {
        $result = $this->fetchRecipes();
        return $result;
    }

    public function displayUsers($email)
    {
        $result = $this->fetchUsers($email);
        return $result;
    }

    public function displayLogin()
    {
        return $this->index();
    }
    public function setCookie($datas)
    {
        return $this->setCookies($datas);
    }
}
