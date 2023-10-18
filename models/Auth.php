<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class Auth
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {

        $this->dbHandler = new InitDB();

    }

    public function registerUser($email, $password, $gender, $phone, $username = null) {
        $sql = "INSERT INTO users (email, password, gender, phone, is_admin, status, username, created_at) VALUES (?, ?, ?, ?, ?, ?, ?,?)";       
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$email, $hashedPassword, $gender, $phone, 0, 0, $username, $date]);         
            if($stmt->rowCount() > 0) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        } catch (PDOException $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }
    }

    public function isUserExists($email) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = ? || username = ?";    
        $stmt = $this->dbHandler->run($sql, [$email, $email]);
        $count = $stmt->fetchColumn();
        if($count > 0) {
            return true; // Returns true if the user exists, false otherwise
        } else  {
            return false; // Return false in case of an error
        }
    }

    public function login($email, $password) {
        $sql = "SELECT email, password FROM users WHERE email = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$email]);
            $user = $stmt->fetch();
            if($user) {
                if (password_verify($password, $user['password'])) {
                    // Passwords match, login successful
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }
        
    }

    public function createToken($email, $token) {
        $sql = "INSERT INTO token_verify (email, token, created_at) VALUES (?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$email, $token, $date]);         
            if($stmt->rowCount() > 0) {
                return true; 
            } else {
                return false; 
            }
        } catch (PDOException $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }
    }

    public function verifyToken($email, $token) {
        $sql = "SELECT * FROM token_verify WHERE email = ? AND token = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$email, $token]);
            $verify = $stmt->fetch();

            if($stmt->rowCount() > 0) {

                $createdAt = (int)$verify['created_at'];
                $currentTime = time();
                $expirationTime = $createdAt + 300; // 5 minutes in seconds

                if ($currentTime > $expirationTime) {
                    $this->deleteToken($email);
                    return 2;
                } else {
                    $this->deleteToken($email);
                    $this->updateStatus($email);
                    return 1;
                }

            } else {
                return 0;
            }
        } catch (\Throwable $th) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }
    }

    private function deleteToken($email) {
        $sql = "DELETE FROM token_verify WHERE email = ?";
        $stmt = $this->dbHandler->run($sql, [$email]);
        if($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    }



    private function updateStatus($email)
    {
        $sql = "UPDATE users SET status = ? WHERE email = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$email, 1]);
            if($stmt) {
               return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }     
    }

    


}


/*  unit test  */

//$test = new Auth();
 //$output = $test->isUserExists("promisedeco2@gmail.com");
//$output = $test->login("promisedeco2@gmail.com", 12);
// $output = $test->registerUser("abc@a.com", 12345, "male", 9823767823, '');
//echo $output ? "true" : "false";