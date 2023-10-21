<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $comment_obj = new CommentManager;
    if(isset($_GET["post_id"]) && isset($_GET["token"]) ) {
        $id = htmlspecialchars($_GET["post_id"]);
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($id)) {
                $response = ["status" => 101, "message" => "Post ID must not be empty"];
            } else {
                if($notify_obj->showComments($id) != false) {
                    $response = ["status" => 201,
                                "message" => "successful", 
                                "data" => $comment_obj->showComments($id)];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "No data to return"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } 
    else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
    
} else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


