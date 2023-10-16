<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class PostManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {

        $this->dbHandler = new InitDB();

    }

    function message($key, $value)
    {
        $this->system_message['status'] = $key;
        $this->system_message['message'] = $value;
    }

}
