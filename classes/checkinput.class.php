<?php

class CheckInput extends Validate
{
    private static array $errorsArray = [];
    public function __construct(
        private  $getDatas
    ) {
        //
    }

    /***
     * Check each inputs - space is not allowed in Username
     */
    public function checkInputs()
    {
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        if (isset($this->getDatas)) {
            /* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $url = array_pop($url); */
            foreach ($this -> getDatas as $key => $value) {
                $result = false;
                try {
                    if (empty($value) || !isset($key)) {
                        //$result = false;
                        $e = throw new Error("Votre $key est vide");
                        array_push(self::$errorsArray, "votre $key est vide");
                        // array_push($this->errorsArray, new Error("votre $key est vide"));
                        //$e = throw new Error((string)header("Location: $url?error=$key-vide"));
                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=$key-vide"));
                        //header("Location: ".Functions::getUrl()."?error=$key-vide");
                    }
                    if (isset($this->getDatas['email']) && (!filter_var($this->getDatas['email'], FILTER_VALIDATE_EMAIL)) /* && !preg_match("[a-z0-9A-Z._-]+@[a-z0-9A-Z._-]+.[a-zA-Z]",$this->getDatas['email']) */) {
                        $e = throw new Error("Veuillez saisir un email valide");
                        array_push(self::$errorsArray, "Veuillez saisir un email valide");
                        // array_push($this->errorsArray, new Error("Veuillez saisir un email valide"));

                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=email-invalid"));
                    }
                    //if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]*$/", $this->getDatas['username'])) { // No space allowed
                    if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]/", $this->getDatas['username'])) { // With space allowed
                        $e = throw new Error('Votre identifiant est invalide');
                        array_push(self::$errorsArray, 'Votre identifiant est invalide');
                        // array_push($this->errorsArray, new Error('Votre identifiant est invalide'));
                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-input"));
                        //throw new Error ('Votre ' . $key . ' est vide ! <br>');
                        //return $result;
                        //die('Nous ne pouvons continuer...');
                    }
                    if (isset($this->getDatas['title']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['title'])) { // With space allowed
                        $e = throw new Error('Ce titre est invalide');
                        array_push($this->errorsArray, new Error('Ce titre est invalide'));
                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-title-input"));
                        //return $e;
                        //return $result = false;
                    }
                    if (isset($this->getDatas['recipe']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['recipe'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                    }
                    if (isset($this->getDatas['comment']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['comment'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                        throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-comment-input"));
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
                    // foreach ($this->errorsArray as $key => $value) {
                    //     echo "".$key."".$value."";
                    // }
                    echo('Erreur : '. $e->getMessage() ." Veuillez remplir les champs s'il vous plait... <br>");
                }
            }
        }
        print_r(self::$errorsArray);
        return $result;
    }

    public static function getErrorsArray()
    {
        print_r(self::$errorsArray);
        return self::$errorsArray;
    }
}
