<?php

class CheckId
{
    /* public $inputId;
    public $errorId;
    public $data; */

    public function __construct(
        private $data, 
        private int $inputId, 
        private string $errorId
        )
    {
        /* $this->data = $data;
        $this->errorId = $errorId;
        $this->inputId = $inputId; */
    }

    public function checkIds(): bool
    {
        //$errorMessage = "";
        $status = true;
        switch (isset($this->data)) {
            case (!isset($this->inputId) || empty($this->inputId)):
                /* $errorMessage = sprintf(
                    "Vous n'avez pas saisit de : %s",
                    $this->input2
                ); */
                echo"Vous n'avez pas saisit d' " . $this->errorId;
                return $status = false;
            default:
                echo "tout va bien";
                break;
        }
        return $status;
    }
    //return
}
