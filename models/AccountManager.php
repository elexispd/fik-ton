<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class AccountManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {

        $this->dbHandler = new InitDB();

    }

    public function getAllUsers() {
        $sql = "SELECT * FROM users WHERE is_admin <> 1";
        try {
            $stmt = $this->dbHandler->run($sql);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    public function getUsers() {
        try {
            $sql = "SELECT * FROM users WHERE is_admin = 0";
            $stmt = $this->dbHandler->run($sql);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return "nothing";
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
        
    }
    
    public function getAllSubAdmins() {
        $sql = "SELECT * FROM users WHERE is_admin <> 2";
        try {
            $stmt = $this->dbHandler->run($sql);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function getUser($email) {   
        $sql = "SELECT * FROM users WHERE email = ? || username = ?";  
        try {  
            $stmt = $this->dbHandler->run($sql, [$email]);
            if($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else  {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function changePassword($email, $password) {
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->dbHandler->run($sql, [$email, $hashedPassword]);
            if($stmt) {
               return true;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }     
    }
    



    function message($key, $value)
    {
        $this->system_message['status'] = $key;
        $this->system_message['message'] = $value;
    }

    


}


/*  unit test  */

$test = new Accountmanager();
// $output = $test->getAllUsers();
$output = $test->changePassword("abc@a.com", "hello");

//print_r($output);

echo $output ? "true" : "false";