<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $notify_obj = new NotifyManager;
            if($notify_obj->getNotifications() == true) {
                $response = ["status" => 1,
                            "message" => "successful", 
                            "data" => $notify_obj->getNotifications()];
            } else {
                http_response_code(200);
                $response = ["status" => 0, "message" => "No data to return"];
            }
        } else {
            http_response_code(403);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    }  else {
        http_response_code(401);
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
    
    
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


