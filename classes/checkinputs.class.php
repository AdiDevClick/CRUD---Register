<?php

class CheckInputs
{
    public $input1;
    public $input2;
    public $data;
    public $submit;
    public $errorName1;
    public $errorName2;

    public function __construct(
        string $input1 = null,
        string $input2 = null,
        $data,
        $submit,
        string $errorName1 = null,
        string $errorName2 = null
    ) {
        $this->input1 = $input1;
        $this->input2 = $input2;
        $this->data = $data;
        $this->submit = $submit;
        $this->errorName1 = $errorName1;
        $this->errorName2 = $errorName2;
    }

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    public function checkInputs()
    {
        //$errorMessage = "";
        $value1 = self::test_input($this->input1);
        $value2 = self::test_input($this->input2);
        $status = true;
        $message = '';
        switch (isset($this->data)) {
            case (empty($value1) && empty($value2)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s,%s",
                    $this->input1,
                    $this->input2
                ); */
                //echo $errorMessage;
                //self::test_input($this->input1) && self::test_input($this->input2);
                echo"Vous n'avez pas saisit de " . $this->errorName1 . " ni de " . $this->errorName2;
                //return $status = false;
                break;
            case (!isset($value1) || empty($value1)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s",
                    $this->input1
                ); */
                //self::test_input($this->input1);
                echo"Vous n'avez pas saisit de " . $this->errorName1 ;
                //return $status = false;
                break;
            case (!isset($value2) || empty($value2)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s",
                    $this->input2
                ); */
                //self::test_input($this->input2);
                echo"Vous n'avez pas saisit de " . $this->errorName2;
                //return $status = false;
                break;
            case (isset($value2)):
                $message = self::test_input($this->input2);
                echo"okay ";
                return $message;
            case (isset($value1)):
                $message = self::test_input($this->input1);
                echo"okay ";
                return $message;

            default:
                $value1 = '';
                $value2 = '';
                echo 'ok';
                break;
        }
        //return $status;
    }
    //return



    public function checkInputs2()
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
    }

    public function checkMessage($message)
    {
        $message = '';
        if (isset($this->data)) {
            $message = self::test_input($message);
        }
    }
}
