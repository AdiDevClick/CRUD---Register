<?php

class CheckInput extends Validate
{

    // private static array $datas = $this->getDatas;
    private static array $errorsArray = [];
    public function __construct(
        private $getDatas,
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
            foreach ($this->getDatas as $key => $value) {
                $result = false;
                // try {
                    if (empty($value) || !isset($key)) {
                        //$result = false;
                        // array_push($this->errorsArray, "votre $key est vide");
                        array_push(self::$errorsArray, "Votre $key est vide");

                        // $e = throw new Error("Votre $key est vide");
                        // array_push(self::$errorsArray, $e);
                        // array_push($this->errorsArray, new Error("votre $key est vide"));
                        //$e = throw new Error((string)header("Location: $url?error=$key-vide"));
                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=$key-vide"));
                        //header("Location: ".Functions::getUrl()."?error=$key-vide");
                    }
                    if (isset($this->getDatas['email']) && (!filter_var($this->getDatas['email'], FILTER_VALIDATE_EMAIL)) /* && !preg_match("[a-z0-9A-Z._-]+@[a-z0-9A-Z._-]+.[a-zA-Z]",$this->getDatas['email']) */) {
                        // array_push($this->errorsArray, "Veuillez saisir un email valide");
                        array_push(self::$errorsArray, "Veuillez saisir un email valide");
                        
                        // $e = throw new Error("Veuillez saisir un email valide");
                        // array_push(self::$errorsArray, $e);
                        // array_push($this->errorsArray, new Error("Veuillez saisir un email valide"));

                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=email-invalid"));
                    }
                    //if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]*$/", $this->getDatas['username'])) { // No space allowed
                    if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]/", $this->getDatas['username'])) { // With space allowed
                        // array_push($this->errorsArray, 'Votre identifiant est invalide');
                        array_push(self::$errorsArray, 'Votre identifiant est invalide');
                        
                        // $e = throw new Error('Votre identifiant est invalide');
                        // array_push(self::$errorsArray, $e);
                        // array_push($this->errorsArray, new Error('Votre identifiant est invalide'));
                        // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-input"));
                        //throw new Error ('Votre ' . $key . ' est vide ! <br>');
                        //return $result;
                        //die('Nous ne pouvons continuer...');
                    }
                    if (isset($this->getDatas['title']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['title'])) { // With space allowed
                        array_push($this->errorsArray, new Error('Ce titre est invalide'));
                        
                        $e = throw new Error('Ce titre est invalide');
                        // array_push(self::$errorsArray, $e);
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
                    }
                    // } else {
                    // $error = sprintf(
                    //     'Les informations envoyées ne permettent pas de vous identifier : (%s)',
                    //     $e->getMessage()
                    // );
                    //$result = [(throw new Error((string)header("Location: $url?error=$key-vide")))];
                    //return $errors;
                    //$errors = [header("Location: $url?error=$key-vide")];
                    //$errors = (string)header("Location: $url?error=$key-vide");
                    //$sanitizedKey = $this->test_input($value);
                    //header("Location: $url?error=$key-vide");
                    // print_r('array error => ' . self::$errorsArray .' <br>');
                    // print_r('array error => ' . $this->errorsArray .' <br>');
                    // print_r('array error => ' . self::$errorsArray .' <br>');
                    // return $result = true;
                    // die('result 2 => ' . $result) ;
                    // } catch (Error $e) {
                // } catch (Error $e) {
                    // foreach ($this->errorsArray as $key => $value) {
                    //     echo "".$key."".$value."";
                    // }
                    // $e = sprintf(
                    //     'Les informations envoyées ne permettent pas de vous identifier : (%s)',
                    //     $e->getMessage()
                    // );
                    // print_r($e);
                    // $result = false;
                    // $this->getErrorsArray();
                    // print_r('array error => ' . self::$errorsArray .' <br>');
                    // print_r(self::$errorsArray);
                    // echo('array error => <br>');
                    // print_r($this->errorsArray);
                    // print_r(self::$errorsArray);

                    // return self::$errorsArray;
                    // echo $e->getMessage();
                    // return $result = false;
                    // echo('result in catch => ' . $result);
                    // print_r($result);
                    // die('Erreur : '. $e->getMessage() ." Veuillez remplir les champs s'il vous plait... <br>");
                // }
            }
        }
        // print_r(self::$errorsArray . "test");
        // print_r($result . 'result2');
        // return $result;
    }

    public static function getErrorMessages() {
        if (!empty(self::$errorsArray)) {
            $datas = isset($this->getDatas);
            foreach (self::$errorsArray as $key => $value) {
                if (str_contains($value, 'password')) self::$errorsArray['errorPassword'] = $value;
                // if (str_contains($value, 'password')) $errorMessage['errorPassword'] = $value;
                elseif (str_contains($value, 'username')) self::$errorsArray['errorUsername'] = $value;
                elseif (str_contains($value, 'email')) self::$errorsArray['errorEmail'] = $value;
                elseif (str_contains($value, 'pwdRepeat')) self::$errorsArray['errorPwdRepeat'] = 'Veuillez confirmer votre mot de passe';
                elseif (str_contains($value, 'age')) self::$errorsArray['age'] = 'Votre âge...';
                elseif (str_contains($value, 'STMTSGNDBCHCNT') && $datas['username']) self::$errorsArray['userTaken'] = "Ce nom d'utilisateur est déjà pris"; //getInputDatas()['username']) self::$errorsArray['userTaken'] = "Ce nom d'utilisateur est déjà pris";
                elseif (str_contains($value, 'STMTSGNDBCHCNT') && $datas['email']) self::$errorsArray['emailTaken'] = "Cet email est déjà pris";

                // elseif (str_contains($value, 'username')) $errorMessage['errorUsername'] = $value;
                else self::$errorsArray['message'] = $value;
                // else $errorMessage = $value;
            }
            // return self::$errorsArray;
        }
        return self::$errorsArray;
    }

    protected function getInputDatas() : Array
    {
        return $this->getDatas;
    }

    public static function getErrorsArray()
    {
        // echo('error dans le static => ');
        // print_r(self::$errorsArray);
        // print_r($this->errorsArray);
        // return false;
        return self::$errorsArray;
        // return $this->errorsArray;
    }

    public static function insertErrorMessageInArray(string $message)
    {
        array_push(self::$errorsArray, $message);
    }
    

    public static function showErrorMessage()
    {
        // $errorMessage = '';
        foreach (self::$errorsArray as $key => $value) {
            // return $errorMessage = $value;
            echo $value . '<br>';
        }
        // echo('error dans le static => ');
        // print_r(self::$errorsArray);
        // print_r($this->errorsArray);
        // return false;
        // return $errorMessage;
        // echo $errorMessage;
        // return $this->errorsArray;
    }
}
