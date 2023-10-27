<?php

class CheckInputs extends Validate
{
    /*   private $input1;
      private $input2;
      private $data;
      private $errorName1;
      private $errorName2; */

    public function __construct(
        private $data,
        private  string $input1 = '',
        private  string $input2 = '',
        private  string $errorName1,
        private  string $errorName2
    ) {
        /*  $this->input1 = $input1;
         $this->input2 = $input2;
         $this->data = $data;
         $this->errorName1 = $errorName1;
         $this->errorName2 = $errorName2; */
    }

    public function checkInputss(): bool
    {
        //$errorMessage = "";
        //$value1 = self::test_input($this->input1);
        //$value2 = self::test_input($this->input2);
        $status = '';
        $message = '';
        switch (isset($this->data)) {


            case (empty($this->input1) && empty($this->input2)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s,%s",
                    $this->input1,
                    $this->input2
                ); */
                //echo $errorMessage;
                //self::test_input($this->input1) && self::test_input($this->input2);
                echo"Vous n'avez pas saisit de " . $this->errorName1 . " ni de " . $this->errorName2;
                return $status = false;
                //$status = false;
                //break;
            case (!isset($this->input1) || empty($this->input1)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s",
                    $this->input1
                ); */
                //self::test_input($this->input1);
                echo"Vous n'avez pas saisit de " . $this->errorName1 ;
                return $status = false;
                //break;
            case (!isset($this->input2) || empty($this->input2)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s",
                    $this->input2
                ); */
                //self::test_input($this->input2);
                echo"Vous n'avez pas saisit de " . $this->errorName2;
                return $status = false;
                //break;
                /*  case (isset($this->input2)):
                     $message = $this->test_input($this->input2);
                     echo $message;
                     //return $message;
                     //break;
                     // no break
                 case (isset($this->input1)):
                     $message = $this->test_input($this->input1);
                     echo $message;
                     return $message; */

                /* default:
                    echo 'ok';
                    return $status = true; */
                //break;
        }
        return $status;
    }
    //return



    /* public function checkInputs2()
    {
        //$errorMessage = "";
        $value1 = $value2 = '';

        $status = true;
        if (isset($this->data)) {
            $value1 = self::test_input($this->input1);
            $value2 = self::test_input($this->input2);
            if (empty($value1) && empty($value2)) {
                echo"Vous n'avez pas saisit de " . $this->errorName1 . " ni de " . $this->errorName2;
                return $status = false;
            }
            if (!isset($value1) || empty($value1)) {
                echo"Vous n'avez pas saisit de " . $this->errorName1 ;
                return $status = false;
            }
            if (!isset($value2) || empty($value2)) {
                echo"Vous n'avez pas saisit de " . $this->errorName2;
                return $status = false;
            }
            if ($value1 === '' && $value2 === '') {
                echo 'ok';
            } else {
                echo 'pas ok';
            }
        }
    } */

}
