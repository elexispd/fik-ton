<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_obj = new PostManager;
    if($post_obj->getDrafts() != false) {
        $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $post_obj->getDrafts()];
    } else {
        $response = ["status" => 100, "message" => "No data to return"];
    }
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


