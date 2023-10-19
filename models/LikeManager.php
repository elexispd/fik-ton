<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class LikeManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createLike($user_id, $post_id) {
        $sql = "INSERT INTO likes (user_id, post_id, created_at) VALUES (?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$user_id, $post_id, $date]);         
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

    
    public function deleteLike($user_id, $post_id) {
        $sql = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$user_id, $post_id]);
            if($stmt->rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function isLiked($user_id, $post_id) {
        $sql = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$user_id, $post_id]);
            if($stmt->rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function totalLikes() {
        $sql = "SELECT COUNT(*) as total FROM likes";
        try {
            $stmt = $this->dbHandler->run($sql);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

   

    function message($key, $value)
    {
        $this->system_message['status'] = $key;
        $this->system_message['message'] = $value;
    }




} 

/*  unit test  */

$test = new LikeManager();
//$output = $test->createLike(1,2);
// $output = $test->deleteLike(1, 2);
//$output = $test->deleteLike(1, 2);
//echo $output ? "true" : "false";
