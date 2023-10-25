<?php

class CheckInput extends Validate
{
    public function __construct(
        private  $getData
    ) {
        //
    }

    public function checkInputs()
    {
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        if (isset($this->getData)) {
            /* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $url = array_pop($url); */
            foreach ($this->getData as $key => $value) {
                $result = false;
                try {
                    if (empty($value) || !isset($key)) {
                        $result = false;
                        //$e = throw new Error((string)header("Location: $url?error=$key-vide"));
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=$key-vide"));
                        //header("Location: ".Functions::getUrl()."?error=$key-vide");
                    }
                    if (isset($this->getData['email']) && (!filter_var($this->getData['email'], FILTER_VALIDATE_EMAIL)) /* && !preg_match("[a-z0-9A-Z._-]+@[a-z0-9A-Z._-]+.[a-zA-Z]",$this->getData['email']) */) {
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=email-invalid"));
                    }
                    if (!preg_match("/^[a-zA-Z0-9]*$/", $this->getData['username'])) {
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-input"));                   
                        //throw new Error ('Votre ' . $key . ' est vide ! <br>');
                        //return $result;
                        //die('Nous ne pouvons continuer...');
                        //$sanitizedKey = $this->test_input($key);

                        //return $e;
                        //return $result = false;
                    } else {
                        //$result = [(throw new Error((string)header("Location: $url?error=$key-vide")))];
                        //return $errors;
                        //$errors = [header("Location: $url?error=$key-vide")];
                        //$errors = (string)header("Location: $url?error=$key-vide");
                        //$sanitizedKey = $this->test_input($value);
                        //header("Location: $url?error=$key-vide");
                        $result = true;
                    }
                } catch (Error $e) {
                    die('Erreur : '. $e->getMessage() ." Veuillez remplir les champs s'il vous plait... <br>");
                }
            }
        }
        return $result;
    }
}
