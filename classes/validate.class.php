<?php

class Validate
{
    //public $data;

    /* public function __construct(
        private bool $data,        
        private $getData,
    ) {
        //$this->data = $data;
    } */

    public function test_input($getData)
    {
        if(is_null($getData) || is_array($getData)) {
            $getData = '';
        }        
        $getData = trim($getData);
        $getData = stripslashes($getData);
        $getData = htmlspecialchars($getData);        
        return $getData;
    }

    /* private function checkMessage($message)
    {
        $message = '';
        if (isset($this->data)) {
            $message = self::test_input($message);
        }
        return $message;
    } */

    /* public function checkInputs()
    {
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        try {
            if (isset($this->data)) {
                foreach ($this->getData as $key => $value) {
                    if (empty($value) || !isset($value)) {
                        //throw new Error ('Votre ' . $key . ' est vide ! <br>');
                        throw new Error((string)header("Location: register.php?error=$key-vide"));
                        //die('Nous ne pouvons continuer...');
                    }
                }
            }
        } catch (Error $e) {
            exit('Erreur : '. $e->getMessage() .'');
        }
    } */
}
