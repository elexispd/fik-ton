<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


 

$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["title"]) && isset($_POST["content"])  && isset($_POST["token"])) {
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);

        $type = (isset($_POST["type"]) ? htmlspecialchars($_POST["type"]) : '');

        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            if(empty($title) && empty($content) ) {
                $response = ["status" => 0, "message" => "Compulsory fields can not be empty"];
            } else {
                $notify_obj = new NotifyManager;
                if($notify_obj->createNotification($title, $content, $type) ) {
                    http_response_code(201);
                    $response = ["status" => 1,
                        "message" => "Notification created successfully"];
                    $topics = $notify_obj->getTopics();
                    $notify_obj->sendNotification($title, $content);
                } else {
                    http_response_code(400);
                    $response = ["status" => 0, "message" => "Notification could not be created"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 0, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

    






echo json_encode($response);


