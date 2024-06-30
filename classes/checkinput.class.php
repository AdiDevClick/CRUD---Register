<?php

class CheckInput extends Validate
{
    // private static array $datas = $data;
    // protected $data;
    private static array $inputToDisplay = [];
    private static array $inputData = [];
    private static array $errorsArray = [];
    // private string $regex = "/^[\w\s,.:_?'!\"éèêëàâäôöûüç-]+$/";


    public function __construct(
        private $getDatas,
    ) {
        // global $messages;
        self::$inputData = $this->getDatas;
        // $this->regex = self::$regex;
        // print_r(self::$inputData);
        // $this->checkInputs();
        // print_r(self::$messages);
    }

    /***
     * Check each inputs - space is not allowed in Username
     */
    public function checkInputs()
    {
        $regex = "/^[\w\s,.:_?'!\"éèêëàâäôöûüç-]+$/";
        // self::$inputData = $this->getDatas;
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        if (isset($this->getDatas)) {
            /* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $url = array_pop($url); */
            foreach ($this->getDatas as $key => $value) {
                $result = false;
                // try {
                if ($key !== 'custom_ingredients' && $key !== 'step_6' && $key !== 'step_5' && $key !== 'step_4' && $key !== 'step_3' && $key !== 'step_2' && $key !== 'file' && $key !== 'img_on_server' && $key !== 'img_status') {
                    $result = true;
                    if (empty($value) || !isset($key)) {
                        array_push(self::$errorsArray, "Votre $key est vide");
                        $result = false;
                    }
                    // echo ($key === 'step_6') . 'test';
                    // array_push($this->errorsArray, "votre $key est vide");
                    // array_push(self::$errorsArray, $key === 'step_6' ||'step_5'||'step_4'||'step_3'||'step_2');

                    // $e = throw new Error("Votre $key est vide");
                    // array_push(self::$errorsArray, $e);
                    // array_push($this->errorsArray, new Error("votre $key est vide"));
                    //$e = throw new Error((string)header("Location: $url?error=$key-vide"));
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=$key-vide"));
                    //header("Location: ".Functions::getUrl()."?error=$key-vide");
                }
                // echo ($key);
                if ($key === 'email' && (!filter_var($key === 'email', FILTER_VALIDATE_EMAIL)) && !preg_match("/([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\.([a-z\.]{2,6})$/", $value)) {
                // if ($key === 'email' && (!filter_var($key === 'email', FILTER_VALIDATE_EMAIL)) && !preg_match("/[a-z0-9A-Z._-]+@[a-z0-9A-Z_-]+.[a-zA-Z]+/", $key === 'email')) {
                // if (isset($this->getDatas['email']) && (!filter_var($this->getDatas['email'], FILTER_VALIDATE_EMAIL)) && !preg_match("/[a-z0-9A-Z._-]+@[a-z0-9A-Z_-]+\.[a-zA-Z]+/", $this->getDatas['email'])) {
                    // array_push($this->errorsArray, "Veuillez saisir un email valide");
                    array_push(self::$errorsArray, "Veuillez saisir un email valide");
                    // $e = throw new Error("Veuillez saisir un email valide");
                    // array_push(self::$errorsArray, $e);
                    // array_push($this->errorsArray, new Error("Veuillez saisir un email valide"));

                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=email-invalid"));
                }
                //if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]*$/", $this->getDatas['username'])) { // No space allowed
                if ($key === 'username' && !preg_match("/^[a-zA-Z0-9]*$/", $value)) { // With space allowed
                // if ($key === 'username' && !preg_match("/^[a-zA-Z0-9]*$/", $this->getDatas['username'])) { // original
                // if (isset($this->getDatas['username']) && !preg_match("/^[a-zA-Z0-9]*$/", $this->getDatas['username'])) { // With space allowed
                    // array_push($this->errorsArray, 'Votre identifiant est invalide');
                    array_push(self::$errorsArray, 'invalidID - Votre identifiant est invalide');

                    // $e = throw new Error('Votre identifiant est invalide');
                    // array_push(self::$errorsArray, $e);
                    // array_push($this->errorsArray, new Error('Votre identifiant est invalide'));
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-input"));
                    //throw new Error ('Votre ' . $key . ' est vide ! <br>');
                    //return $result;
                    //die('Nous ne pouvons continuer...');
                }
                // if ($key === 'title' ) { // With space allowed

                if ($result && !empty($key === 'title') && $key === 'title' && !preg_match($regex, $value)) { // With space allowed
                // if ($result && !empty($key === 'title') && !preg_match("/^[\w\s,.:_?'!\"éèêëàâäôöûüç-]+$/", $this->getDatas['title'])) { // original
                // if ($key === 'title' && !preg_match("/(^[\w\s,.:_?'%!éèêëàâäôöûüç-]+$)/", $key === 'title')) { // With space allowed
                // if (isset($this->getDatas['title']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['title'])) { // With space allowed
                    array_push(self::$errorsArray, 'invalidTitle - Ce titre est invalide');
                    
                    // array_push($this->errorsArray, 'Ce titre est invalide');
                    // array_push($this->errorsArray, new Error('Ce titre est invalide'));

                    // $e = throw new Error('Ce titre est invalide');
                    // array_push(self::$errorsArray, $e);
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-title-input"));
                    //return $e;
                    //return $result = false;
                }
                // if ($key === 'step_1' || $key === 'title') array_push(self::$errorsArray, 'invalidRecipe - Cette recette est invalide' . $key);
                // if (!empty($value[$key === 'recipe']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['recipe'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                //     array_push(self::$errorsArray, 'invalidRecipe - Cette recette est invalide');
                    
                //     // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                // }
                if ($result && !empty($key === 'step_1') && $key === 'step_1' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_1') && !preg_match("/^[\w\s,.:_?'!\"éèêëàâäôöûüç-]+$/", $this->getDatas['step_1'])) { // original
                // if (isset($key['step_1']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_1'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep1 - La première étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($result && !empty($key === 'step_2') && $key === 'step_2' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_2') && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_2'])) { // original
                // if (isset($this->getDatas['step_2']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_2'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep2 - La deuxième étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($result && !empty($key === 'step_3') && $key === 'step_3' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_3') && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_3'])) { // original
                // if (isset($this->getDatas['step_3']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_3'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep3 - La troisième étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($result && !empty($key === 'step_4') && $key === 'step_4' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_4') && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_4'])) { // Original
                // if (isset($this->getDatas['step_4']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_4'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep4 - La quatrième étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($result && !empty($key === 'step_5') && $key === 'step_5' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_5') && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_5'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if (isset($this->getDatas['step_5']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_5'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep5 - La cinquième étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($result && !empty($key === 'step_6') && $key === 'step_6' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($result && !empty($key === 'step_6') && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_6'])) { // original"
                // if (isset($this->getDatas['step_6']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['step_6'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep6 - La sixième étape de cette recette est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-recipe-input"));
                }
                if ($key === 'comment' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if ($key === 'comment' && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['comment'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                // if (isset($this->getDatas['comment']) && !preg_match("(^[\w\s,.:_?'!éèêëàâäôöûüç-]+$)", $this->getDatas['comment'])) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidComment - Ce commentaire est invalide');
                    
                    // throw new Error((string)header("Location: ".Functions::getUrl()."?error=invalid-comment-input"));
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

    public static function getErrorMessages(): array
    {

        // $datas = new CheckInput(self::$messages);
        // print_r(self::getInputDatas());
        if (!empty(self::$errorsArray)) {
            // $datas = isset($this->getDatas);
            foreach (self::$errorsArray as $key => $value) {
                if (str_contains($value, 'password')) {
                    self::$errorsArray['errorPassword'] = $value;
                }
                // if (str_contains($value, 'password')) $errorMessage['errorPassword'] = $value;
                elseif (str_contains($value, 'username')) {
                    self::$errorsArray['errorUsername'] = $value;
                } elseif (str_contains($value, 'invalidID')) {
                    self::$errorsArray['invalidID'] = $value;
                } elseif (str_contains($value, 'invalidTitle')) {
                    self::$errorsArray['invalidTitle'] = $value;
                } elseif (str_contains($value, 'pwdRepeat')) {
                    self::$errorsArray['errorPwdRepeat'] = 'Confirmer votre mot de passe';
                } elseif (str_contains($value, 'age')) {
                    self::$errorsArray['age'] = 'Votre âge...';
                } elseif (str_contains($value, 'STMTSGNDBCHCNTEM')) {
                    self::$errorsArray['emailTaken'] = "Cet email est déjà pris";
                } elseif (str_contains($value, 'SGNPWM ')) {
                    self::$errorsArray['pwMatch'] = "Les mots de passes ne sont pas identiques";
                } elseif (str_contains($value, 'STMTSGNDBCHCNT')) {
                    self::$errorsArray['userTaken'] = "Ce nom d'utilisateur est déjà pris";
                } //getInputDatas()['username']) self::$errorsArray['userTaken'] = "Ce nom d'utilisateur est déjà pris";
                elseif (str_contains($value, 'STMTLGNGETPWCNT')) {
                    self::$errorsArray['userError'] = "Nom d'utilisateur ou Mot de passe incorrect";
                } //getInputDatas()['username']) self::$errorsArray['userTaken'] = "Ce nom d'utilisateur est déjà pris";
                // elseif (str_contains($value, 'username')) $errorMessage['errorUsername'] = $value;
                elseif (str_contains($value, 'email')) {
                    self::$errorsArray['errorEmail'] = $value;
                } elseif (str_contains($value, 'LGNGETPW')) {
                    self::$errorsArray['userError'] = "Nom d'utilisateur ou Mot de passe incorrect";
                } elseif (str_contains($value, 'invalidRecipeStep')) {
                    self::$errorsArray['invalidRecipeSteps'] = $value;
                } else {
                    self::$errorsArray['message'] = $value;
                }
            }
        }
        return self::$errorsArray;
    }

    protected function getInputDatas(): array
    {
        return $this->getDatas;
    }

    public static function getInputValue(string $input)
    {
        // print_r(self::$inputData[$input] . PHP_EOL . ' mon self input');
        if (isset(self::$inputData[$input])) {
            return self::$inputData[$input];
        }
    }

    /**
     * Accède à l'array d'erreur global
     *
     * @return array
     */
    public static function getErrorsArray()
    {
        return self::$errorsArray;
    }

    public static function insertErrorMessageInArray(string $message)
    {
        array_push(self::$errorsArray, $message);
    }


    /**
     * Retourne un message d'erreur en fonction de l'erreur
     * @return string
     */
    public static function showErrorMessage(): string
    {
        $errorMessage = '';
        foreach (self::$errorsArray as $key => $value) {
            // print_r($key . PHP_EOL .' => ' . $value . '<br>');
            if ($key === 'emailTaken' && isset(self::$errorsArray ['userTaken']) || $key === 'userTaken' && isset(self::$errorsArray ['emailTaken'])) {
                $errorMessage = 'Cet utilisateur et email sont déjà pris';
            } elseif ($key === 'emailTaken') {
                $errorMessage = $value;
                // print_r($key);
            } elseif ($key === 'userTaken') {
                $errorMessage = $value;
                // print_r($errorMessage);
            } elseif ($key === 'pwMatch') {
                $errorMessage = $value;
                // print_r($errorMessage);
            } elseif ($key === 'errorEmail') {
                $errorMessage = 'Cet email est invalide';
            } elseif (str_contains($value, 'vide')) {
                $errorMessage = 'Un ou plusieurs champs sont vides';
            } elseif ($key === 'invalidID') {
                $errorMessage = 'Votre identifiant est invalide';
            } elseif ($key === 'invalidTitle') {
                $errorMessage = 'Le titre de votre recette est invalide';
            } elseif ($key === 'invalidRecipeSteps') {
                $errorMessage = $value;
            }
        }
        return $errorMessage;
    }

    /**
     * Crer une input avec une classe d'erreur
     *
     * @param string $name
     * @param string $type
     * @param string $error la clé de l'erreur définie dans l'array global
     * @param string|null $id
     * @param string|null $autoComplete permet de définir l'auto complete d'accessibilité
     * @return string
     */
    public static function showInputError(string $name, string $type, string $error, string $id = null, string $autoComplete = null): string
    {
        // $error === 'pwMatch' ? $error = '*****' : null;
        // $message = strip_tags(self::$errorsArray[$error]);
        $message = ($error === 'pwMatch') ? $error = 'Votre mot de passe...' : strip_tags(self::$errorsArray[$error]);

        $errorMessage = '
        <input 
            class="input_error" 
            name="'.$name.'" 
            type="'.($type ?? $name).'" 
            id="'.($id ?? $name).'" 
            placeholder="'. ($message ?: $name) .'" 
            autocomplete="'.$autoComplete.'"
        />';
        return $errorMessage;
    }
}
