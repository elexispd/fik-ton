<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["title"]) && isset($_POST["content"]) && isset($_FILES["thumbnail"]) && isset($_POST["video_link"]) && isset($_POST["genre"]) && isset($_POST["author"])) {
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);

        $type = (isset($_POST["type"]) ? htmlspecialchars($_POST["type"]) : '');
        

        if(empty($title) && empty($content) ) {
            $response = ["status" => 101, "message" => "Compulsory fields can not be empty"];
        } else {
            $notify_obj = new NotifyManager;
            if($notify_obj->createNotification($title, $content, $type) ) {
                $response = ["status" => 201,
                     "message" => "Notification created successfully"];
            } else {
                $response = ["status" => 100, "message" => "Notification could not be created"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameters"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}




echo json_encode($response);


