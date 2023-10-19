<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $notify_obj = new NotifyManager;
    if(isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
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
    } 
    else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
    
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


