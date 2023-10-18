<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/MailManager.php");

require_once(__DIR__ . "/InitDB.php");


class NotifyManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createNotification($title, $content, $type) {
        $sql = "INSERT INTO notifications (title, content, type, created_at) VALUES (?, ?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$title, $content, $type, $date]);         
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

    public function getNotifications() {
        $sql = "SELECT * FROM notifications";
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

    public function getNotication($id) {
        $sql = "SELECT * FROM notifications WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$id]);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetch();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function totalNotications() {
        $sql = "SELECT * FROM notifications";
        try {
            $stmt = $this->dbHandler->run($sql);
                return $stmt->rowCount();
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function deleteNotification($id) {
        $sql = "DELETE FROM notifications WHERE id = ?";
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

$test = new NotifyManager();
//  $output = $test->createNotification("New Show", "testing notify", 1);



