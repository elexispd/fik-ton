<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $like_obj = new LikeManager;
    $total = $like_obj->totalLikes();
    $response = ["status" => 201, "message" => "successfull", "data" => $total];
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


