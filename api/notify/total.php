<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_obj = new NotifyManager;
    $total = $post_obj->totalNotications();
    $response = ["status" => 201, "message" => "successfull", "data" => $total];
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


