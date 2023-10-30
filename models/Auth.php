<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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

    public function registerUser($email, $password, $gender, $phone, $topic, $username = null) {
        $sql = "INSERT INTO users (email, password, gender, phone, is_admin, status, username, notify_topic, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";       
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$email, $hashedPassword, $gender, $phone, 0, 0, $username, $topic, $date]);         
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
        $sql = "SELECT id, email, status, password, phone, gender, is_admin, status FROM users WHERE email = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$email]);
            $user = $stmt->fetch();
            if($user) {
                if (password_verify($password, $user['password'])) {
                    // Passwords match, login successful
                    if($user["status"] == 1) {
                        return $user;
                    } else {
                        return -1;
                    }
                    
                } else {
                    return -1;
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
            $stmt = $this->dbHandler->run($sql, [1, $email]);
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

    public function auth($id, $password) {
        
        $sql = "SELECT id, password FROM users WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$id]);
            $user = $stmt->fetch();
            if($user) {
                if (password_verify($password, $user['password'])) {
                    // Passwords match, login successfu
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

    // public function encrypt($data) {
    //     $key = md5('fik');
    //     $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    //     $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    //     return base64_encode($encrypted . ',' . $iv);
    // }

    // public function decrypt($encryptedData) {
    //     $key = md5('fik');
    //     // Use a comma as the separator, consistent with the encryption part
    //     $explodedData = explode(',', base64_decode($encryptedData), 2);
    //     // Check if the explode was successful
    //     if (count($explodedData) === 2) {
    //         list($data, $iv) = $explodedData;
    //         return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
    //     } else {
    //         // Handle the case where the explode was not successful
    //         return false; // Or any other appropriate error handling
    //     }
    // }
    
    public function encrypt($data) {
    $key = md5('fik');
    $iv = openssl_random_pseudo_bytes(16); // Ensure the IV is 16 bytes
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . ',' . $iv);
    }
    
    public function decrypt($encryptedData) {
        $key = md5('fik');
        // Use a comma as the separator, consistent with the encryption part
        $explodedData = explode(',', base64_decode($encryptedData), 2);
        // Check if the explode was successful
        if (count($explodedData) === 2) {
            list($data, $iv) = $explodedData;
            $iv = str_pad($iv, 16, "\0"); // Pad the IV to 16 bytes if needed
            return openssl_decrypt($data, 'aes-256-cbc', $key, 0, $iv);
        } else {
            // Handle the case where the explode was not successful
            return false; // Or any other appropriate error handling
        }
    }

    public function authorize($token) {
        $token = $this->decrypt($token);
        $data = explode(",", $token);

        $userId = isset($data[0]) ? $data[0] : null;
        $password = isset($data[1]) ? $data[1] : null;
               
        if($this->auth($userId, $password)) {
            return $data;
        } else {
            return false;
        }
    }
    


}


/*  unit test  */

//$test = new Auth();
 //$output = $test->isUserExists("promisedeco2@gmail.com");
//$output = $test->login("promisedeco2@gmail.com", 12);
// $output = $test->registerUser("abc@a.com", 12345, "male", 9823767823, '');
//echo $output ? "true" : "false";