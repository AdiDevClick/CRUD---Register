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

    /**
     * Check each inputs - space is not allowed in Username
     */
    public function checkInputs()
    {
        $regex = "/^[\w\s,.:_?'!\"*()~&éèêëàâäôöûüùçÀîÉ-]+$/";
        // $regex = "/^[\w\s,.:_?'!\"éèêëàâäôöûüçÀîÉ-]+$/";
        $canBeEmpty = [
            'custom_ingredients',
            'step_6',
            'step_5',
            'step_4',
            'step_3',
            'file',
            'img_on_server',
            'img_status',
            'resting_time',
            'video_link'
        ];
        // self::$inputData = $this->getDatas;
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        if (isset($this->getDatas)) {
            // echo json_encode($this->getDatas);

            /* $url = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $url = array_pop($url); */
            foreach ($this->getDatas as $key => $value) {
                $result = false;
                // try {
                if (!in_array($key, $canBeEmpty)) {
                    $result = true;
                    if (empty($value) || !isset($key) || (is_numeric($key) && $key == 0)) {
                        array_push(self::$errorsArray, "Votre $key est vide");
                        $result = false;
                    }
                }
                if ($key === 'email' && (!filter_var($key === 'email', FILTER_VALIDATE_EMAIL)) && !preg_match("/([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\.([a-z\.]{2,6})$/", $value)) {
                    array_push(self::$errorsArray, "Veuillez saisir un email valide");
                }
                if ($key === 'username' && !preg_match("/^[a-zA-Z0-9._@-]+$/", $value)) { // With space allowed
                    // if ($key === 'username' && !preg_match("/^[a-zA-Z0-9._-]+(@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})?$/", $value)) { // With space allowed
                    array_push(self::$errorsArray, 'invalidID - Votre identifiant est invalide');
                }
                if ($key === 'title' && !$result && !preg_match($regex, $value)) { // With space allowed
                    array_push(self::$errorsArray, 'invalidTitle - Ce titre est invalide');
                }
                if ($result && !empty($key === 'step_1') && $key === 'step_1' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep1 - La première étape de cette recette est invalide');
                }
                if ($result && !empty($key === 'step_2') && $key === 'step_2' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep2 - La deuxième étape de cette recette est invalide');
                }
                if ($result && !empty($key === 'step_3') && $key === 'step_3' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep3 - La troisième étape de cette recette est invalide');
                }
                if ($result && !empty($key === 'step_4') && $key === 'step_4' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep4 - La quatrième étape de cette recette est invalide');
                }
                if ($result && !empty($key === 'step_5') && $key === 'step_5' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep5 - La cinquième étape de cette recette est invalide');
                }
                if ($result && !empty($key === 'step_6') && $key === 'step_6' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidRecipeStep6 - La sixième étape de cette recette est invalide');
                }
                if ($key === 'comment' && !preg_match($regex, $value)) { // With space allowed "/^[a-zA-Z0-9]*\z/"
                    array_push(self::$errorsArray, 'invalidComment - Ce commentaire est invalide');
                }
                if (!$result && $key === 'review') {
                    array_push(self::$errorsArray, 'invalidReview - Veuillez noter le produit pour pouvoir commenter');
                }
            }
        }
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
                } elseif (str_contains($value, 'username')) {
                    self::$errorsArray['errorUsername'] = $value;
                } elseif (str_contains($value, 'invalidID')) {
                    self::$errorsArray['invalidID'] = $value;
                } elseif (str_contains($value, 'invalidTitle')) {
                    self::$errorsArray['invalidTitle'] = "Ce titre est invalide";
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
                } elseif (str_contains($value, 'invalidComment')) {
                    self::$errorsArray['invalidComment'] = "Ce commentaire est invalide";
                } elseif (str_contains($value, 'invalidReview')) {
                    self::$errorsArray['invalidReview'] = "Veuillez noter le produit pour pouvoir continuer";
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
        $keysWillShowValues = [
            'emailTaken',
            'invalidRecipeSteps',
            'userTaken',
            'pwMatch',
            'invalidComment',
            'invalidTitle',
            'invalidReview',
            'invalidID',
        ];
        $count = 0;
        $errorMessage = '';
        foreach (self::$errorsArray as $key => $value) {
            if ($key === 'emailTaken' && isset(self::$errorsArray['userTaken']) || $key === 'userTaken' && isset(self::$errorsArray['emailTaken'])) {
                $errorMessage .= 'Cet utilisateur et email sont déjà pris';
            } elseif (!str_contains($value, 'review') && str_contains($value, 'vide') && $count === 0 && $key !== 'message') {
                $count++;
                $errorMessage .= 'Un ou plusieurs champs sont vides';
            } elseif (
                // If the key is in the array, we display the value set in self::$errorsArray
                in_array($key, $keysWillShowValues) && $count === 0
            ) {
                $errorMessage .= $value;
            } elseif ($key === 'errorEmail') {
                $errorMessage .= 'Cet email est invalide';
            } elseif ($key === 'invalidID' && $count === 0) {
                $errorMessage .= 'Votre identifiant est invalide';
            }
        }
        return $errorMessage;
    }

    /**
     * Crer une input avec une classe d'erreur
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
            name="' . $name . '" 
            type="' . ($type ?? $name) . '" 
            id="' . ($id ?? $name) . '" 
            placeholder="' . ($message ?: $name) . '" 
            autocomplete="' . $autoComplete . '"
        />';
        return $errorMessage;
    }

    /**
     * Sanitize les données passée en paramètre
     * @param array $options Une clé à ne pas intégrer dans l'array ou un htmlspecialchar à ne pas réaliser
     * @return string[]
     */
    public function sanitizeData(array $options = null)
    {
        $this->getDatas = is_array($this->getDatas) ? $this->getDatas : [$this->getDatas];

        // Converts into HTMLSUPERCHARS
        $convert = true;

        if (isset($options['convert'])) {
            $convert = $options['convert'];
        }

        foreach ($this->getDatas as $key => $value) {
            if (isset($options['key'])) {
                if ($key !== $options['key']) {
                    $sanitized_Datas[$key] = $this->test_input($value, $convert);
                }
            } else {
                $sanitized_Datas[$key] = $this->test_input($value, $convert);
            }
        }
        $this->checkInputs();

        if (empty($this->getErrorsArray())) {
            $status = true;
            $_SESSION['SANITIZED'] = $status;
        } else {
            $status = false;
            $_SESSION['SANITIZED'] = $this->getErrorsArray();
        }

        return $sanitized_Datas;
    }
}
