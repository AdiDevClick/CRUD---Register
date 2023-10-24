<?php

class SignupView extends SignupController 
{
    public function setUsers()
    {
        return $this->signupUsers();
    }
}