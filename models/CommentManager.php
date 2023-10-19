<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class CommentManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createComment($user_id, $post_id) {
        $sql = "INSERT INTO comments (user_id, post_id, created_at) VALUES (?, ?, ?)";       
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

    
    public function deleteComment($user_id, $post_id) {
        $sql = "DELETE FROM comments WHERE (user_id = ? || is_admin = ? || is_admin = ?) AND post_id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$user_id, $user_id, $user_id, $post_id]);
            if($stmt->rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function showComments($post_id) {
        $sql = "SELECT * FROM comments WHERE post_id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$post_id]);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function totalComments($post_id) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE post_id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$post_id]);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    

   

   



} 

/*  unit test  */

// $test = new BookmarkManager();
//$output = $test->createBookmark(1,2);
// $output = $test->deleteBookmark(1, 2);
// $output = $test->isBookmarked(1, 2);
//  echo $output ? "true" : "false";
