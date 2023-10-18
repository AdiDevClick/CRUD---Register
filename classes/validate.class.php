<?php

class Validate
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function checkMessage($message)
    {
        $message = '';
        if (isset($this->data)) {
            $message = self::test_input($message);
        }
        return $message;
    }

}
