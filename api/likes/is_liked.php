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
                $response = ["status" => 0, "message" => "All parameter are required"];
            } else {
                $like_obj = new LikeManager;
                $post_id = $_GET["post_id"];
                if($like_obj->isLiked($user[0], $post_id) != false) {
                    $response = ["status" => 1,
                                "message" => "Post has been liked", 
                                ];
                                http_response_code(202);
                } else {
                    http_response_code(200);
                    $response = ["status" => 0, "message" => "Post is not liked"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid parameters"];
    }
    
    
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


