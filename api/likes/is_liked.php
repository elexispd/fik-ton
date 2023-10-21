<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"]) && isset($_GET["post_id"])  ) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($_GET["post_id"])) {
                $response = ["status" => 100, "message" => "All parameter are required"];
            } else {
                $like_obj = new LikeManager;
                $post_id = $_GET["post_id"];
                if($like_obj->isLiked($user[0], $post_id) != false) {
                    $response = ["status" => 201,
                                "message" => "Post has been liked", 
                                ];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Post is not liked"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 105, "message" => "Invalid parameters"];
    }
    
    
 } else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


