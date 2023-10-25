<?php

class SignupView extends SignupController
{
    public function setUsers()
    {
        return $this->signupUsers();
    }

    /* public function displaySignup($e) 
    {
    $displayForm = '';
    //if (!isset($_SESSION['LOGGED_USER']) && !isset($_SESSION['REGISTERED_USER'])) {
       /*  $displayForm = '<form action="register.php" method="post">';
        if (isset($e)) {
            $displayForm .= '<div class="alert-error">';
            echo $e;
            $displayForm .= '</div>';
        } 

        $displayForm = '<label for="username" class="label">Votre prénom et nom :</label>';
        $displayForm .= '<input name="username" class="input" type="text" id="username" placeholder="Votre nom et prénom..."/>';

        $displayForm .= '<label for="email" class="label">Votre email :</label>';
        $displayForm .= '<input name="email" class="input" type="email" id="email" placeholder="Votre email..."/>';

        $displayForm .= '<label for="password" class="label">Votre mot de passe :</label>';
        $displayForm .= '<input name="password" class="input" type="password" id="password" placeholder="*****" />';

        $displayForm .= '<label for="pwdRepeat" class="label">Confirmez votre mot de passe :</label>';
        $displayForm .= '<input name="pwdRepeat" class="input" type="password" id="pwdRepeat" placeholder="*****"/>';

        $displayForm .= '<label for="age" class="label">Votre âge :</label>';
        $displayForm .= '<input name="age" type="number" class="input" id="age" placeholder="Votre âge..."/>';

        $displayForm .= "<button type='submit' name='submit' class='btn'>S'enregistrer</button>";
        //$displayForm .= '</form>';
        echo $displayForm;
    } */

    
    

    public function displaySignupSuccess($getDatas)
    {
        $successMessage = '';
        //if (isset($_SESSION['REGISTERED_USER'])) {
            $successMessage = '<div class="alert-success">';
            $successMessage .= '<section class="container">';
            $successMessage .= '<div class="form-flex">';
            $successMessage .= '<h1>Votre profil à bien été enregistré !</h1>';
            $successMessage .= '<div class="card">';
            $successMessage .= '<div class="card-body">';
            $successMessage .= '<h5>Rappel de vos informations :</h5>';
            $successMessage .= '<p><b>Votre nom</b> : ' . strip_tags($getDatas['username']) . '</p>';
            $successMessage .= '<p><b>Votre email</b> : ' . strip_tags($getDatas['email']) . '</p>';
            $successMessage .= '<p><b>Votre âge</b> : ' . strip_tags($getDatas['age']) . '</p>';
            $successMessage .= '<p>Vous pouvez maintenant vous identifier, passez une bonne journée '. strip_tags($getDatas['username']) .' !</p>';
            $successMessage .= '</div>';
            $successMessage .= '</div>';
            $successMessage .= '</div>';
            $successMessage .= '</section>';
            echo $successMessage;
        }
    }

