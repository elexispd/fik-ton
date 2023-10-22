<?php


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
        $sql = "SELECT id, email gender, phone, username, created_at FROM users WHERE is_admin <> 1";
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
            $sql = "SELECT id, email gender, phone, username, created_at FROM users WHERE is_admin = 0";
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
    
    public function getAllSubAdmins() {
        $sql = "SELECT id, email gender, phone, username, created_at FROM users WHERE is_admin = 2";
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

    public function getUser($email, $user_id) {   
        $sql = "SELECT id, email gender, phone, username, created_at  FROM users WHERE email = ? || id = ?";  
        try {  
            $stmt = $this->dbHandler->run($sql, [$email, $user_id]);
            if($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else  {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function getUserByID($id) {   
        $sql = "SELECT id, email gender, phone, username, is_admin, created_at  FROM users WHERE id = ?";  
        try {  
            $stmt = $this->dbHandler->run($sql, [$id]);
            if($stmt->rowCount() > 0) {
                return $stmt->fetch();
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
            $stmt = $this->dbHandler->run($sql, [$hashedPassword, $email]);
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

    public function totalUsers() {
        $sql = "SELECT COUNT(*) AS total FROM users WHERE is_admin <> 1";
        try {
            $stmt = $this->dbHandler->run($sql);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$id]);
            if($stmt->rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }



   
    




    


}


/*  unit test  */

