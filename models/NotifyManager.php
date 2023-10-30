<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/MailManager.php");

require_once(__DIR__ . "/InitDB.php");
define("API_ACCESS_KEY", 'AAAAXXRYi2Y:APA91bG9KniOzglH3Y_Uiznr6qCqSHepU2iUyRkTSQl947tG8tdliWwf8DYrq_Gw9DDA40dFovyTklFS-P_EN8QaggX3hhRkeSDGdJ7xSnca2fpgZX0oWHp5cJQzJJbV-hTjdTOvx3xb');

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
        $sql = "SELECT COUNT(*) as total FROM notifications";
        try {
            $stmt = $this->dbHandler->run($sql);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
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
    
    public function getTopics() {
        $sql = "SELECT notify_topic  FROM users WHERE notify_topic = ?";  
        try {  
            $topic = "subscribed_users";
            $stmt = $this->dbHandler->run($sql, [$topic]);
            if($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else  {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    
    public function sendNotification($title, $body) {
        $topic = "subscribed_users";
        $msg = [
            'body' => $body,
            'title' => $title,
            'vibrate' => 1,
            'sound' => 1,
        ];

        $fields = [
                'to' => '/topics/' . $topic,
                'notification'          => $msg
            ];

        $headers = [ 
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
        //echo $result;
    }


   
   
} 

/*  unit test  */

// $test = new NotifyManager();
//  $output = $test->createNotification("New Show", "testing notify", 1);



