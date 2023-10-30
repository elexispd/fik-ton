<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $notify_obj = new NotifyManager;
    if(isset($_GET["notify_id"]) && isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $id = htmlspecialchars($_GET["notify_id"]);
            if(empty($id)) {
                $response = ["status" => 0, "message" => "ID must not be empty"];
            } else {
                if($notify_obj->getNotication($id) != false) {
                    $response = ["status" => 1,
                                "message" => "successful", 
                                "data" => $notify_obj->getNotication($id)];
                } else {
                    $response = ["status" => 0, "message" => "No data to return"];
                    http_response_code(200);
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } 
    else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
    
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


