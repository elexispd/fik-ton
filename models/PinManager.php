<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class PinManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createPin($content, $thumbnail, $videoLink, $author) {
        $sql = "INSERT INTO pinned (content, thumbnail, video_link, author, created_at) VALUES (?, ?, ?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$content, $thumbnail, $videoLink, $author, $date]);         
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
    
    public function unpin($id) {
        $date = time();
        $status = 0;
        $sql = "UPDATE pinned SET status = ?, updated_at = ?  WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$status, $date, $id]);
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
    
    
    public function listPinned(){
        $sql = "SELECT * FROM pinned WHERE status = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [1]);
            if($stmt->rowCount() > 0 ) {
                $pinned = $stmt->fetchAll();
                return $pinned;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    
    
    
    
    
}