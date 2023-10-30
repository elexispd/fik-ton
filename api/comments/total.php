<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["post_id"])  && isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($_GET["post_id"])) {
                $response = ["status" => 0, "message" => "Post ID is required" ];
            } else {
                $post_id = $_GET["post_id"];
                $comment_obj = new CommentManager;
                $total = $comment_obj->totalComments($post_id);
                $response = ["status" => 1, "message" => "successfull", "data" => $total];
            }
        } else {
            http_response_code(200);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    
    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameter" ];
    }
    
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}



echo json_encode($response);