<?php

class CheckInput extends Validate
{
    public function __construct(
        private $data,
        private $getData,
    ) {
        //
    }

    public function checkInputs()
    {
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        try {
            if (isset($this->data) && isset($this->getData)) {
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

    }
}
