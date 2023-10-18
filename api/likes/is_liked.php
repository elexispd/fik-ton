<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $like_obj = new LikeManager;
    $user_id = $_GET['user_id'];
    $post_id = $_GET["post_id"];
    if($like_obj->isLiked($user_id, $post_id) != false) {
        $response = ["status" => 201,
                     "message" => "Post has been liked", 
                     ];
    } else {
        $response = ["status" => 100, "message" => "Post is not liked"];
    }
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


