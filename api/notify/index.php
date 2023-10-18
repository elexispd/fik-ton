<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $notify_obj = new NotifyManager;
    if($notify_obj->getNotifications() != false) {
        $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $notify_obj->getNotifications()];
    } else {
        $response = ["status" => 100, "message" => "No data to return"];
    }
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


