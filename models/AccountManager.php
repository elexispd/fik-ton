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
    
    public function getUserByEmail($email) {   
        $sql = "SELECT id, email gender, phone, username, created_at  FROM users WHERE email = ?";  
        try {  
            $stmt = $this->dbHandler->run($sql, [$email]);
            if($stmt->rowCount() > 0) {
                return $stmt->fetch();
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
    
    public function getUsersTotalByRegistrationMonth() {
            // Get the current year
            $currentYear = date("Y");
        
            $months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];
        
            $sql = "SELECT MONTH(FROM_UNIXTIME(created_at)) AS month_number, COUNT(*) AS user_count
                    FROM users
                    WHERE YEAR(FROM_UNIXTIME(created_at)) = ?
                    GROUP BY month_number
                    ORDER BY month_number";
        
            try {
                $stmt = $this->dbHandler->run($sql, [$currentYear]);
                $results = $stmt->fetchAll();
        
                $formattedResults = array_fill_keys($months, 0);
        
                foreach ($results as $row) {
                    $monthName = date("M", mktime(0, 0, 0, $row['month_number'], 1, 2000));
                    $formattedResults[$monthName] = (int)$row['user_count'];
                }
        
                return $formattedResults;
            } catch (\Throwable $e) {
                // Handle the database error
                return "Database Error: " . $e->getMessage();
            }
        }
        
        





   
    




    


}


/*  unit test  */

