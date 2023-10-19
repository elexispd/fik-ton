<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");


$response = [];

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $post_id = $_GET["post_id"];
    $comment_obj = new CommentManager;
    $total = $comment_obj->totalComments($post_id);
    $response = ["status" => 201, "message" => "successfull", "data" => $total];
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);