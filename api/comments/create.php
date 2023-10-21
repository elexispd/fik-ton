<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"]) && isset($_POST["token"]) ) {

        $post_id = htmlspecialchars($_POST["post_id"]);
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {       
            if(empty($post_id) ) {
                $response = ["status" => 101, "message" => "Compulsory fields can not be empty"];
            } else {
                $comment_obj = new CommentManager;
                if($comment_obj->createComment($user[0], $post_id) ) {
                    $response = ["status" => 201,
                        "message" => "Comment added"];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Something went wrong"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}




echo json_encode($response);


