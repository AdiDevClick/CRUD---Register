<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);   

function customErrorHandlers($errno, $errstr, $errfile, $errline)
{
    $message = "Errors : [$errno] $errstr - $errfile:$errline";
    error_log($message . PHP_EOL, 3, "error_log.txt");
}

set_error_handler("customErrorHandlers");
