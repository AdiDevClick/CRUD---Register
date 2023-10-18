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
            if ($this->data) 
            {   
                foreach ($this->getData as $key => $value) 
                {
                    if (empty($value) || !isset($value)) 
                    {   
                        throw new Error ('Votre ' . $key . ' est vide ! <br>');
                        
                        //die('Nous ne pouvons continuer...');                
                    }
                }
            }
        } catch (Error $error) {
            exit ('Erreur : '. $error->getMessage() .'');
            
        }
            
    }
}

