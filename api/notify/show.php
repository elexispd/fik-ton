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
                $response = ["status" => 101, "message" => "ID must not be empty"];
            } else {
                if($notify_obj->getNotication($id) != false) {
                    $response = ["status" => 201,
                                "message" => "successful", 
                                "data" => $notify_obj->getNotication($id)];
                } else {
                    $response = ["status" => 100, "message" => "No data to return"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } 
    else {
        http_response_code(403);
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
    
} else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


