<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");
require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"]) && isset($_POST["token"]) ) {
        $post_id = $_POST["post_id"];
        $auth_obj = new Auth;
        $post_obj = new PostManager;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            if(empty($post_id)) {
                $response = ["status" => 0, "message" => "PostID field is required"];
            } else {
                $like_obj = new LikeManager;
                if($like_obj->deleteLike($user[0], $post_id) ) {
                    $post_obj->decreasePostLike($post_id);
                    $response = ["status" => 1,
                        "message" => "Like removed successfully"];
                } else {
                    http_response_code(500);
                    $response = ["status" => 0, "message" => "Like could not be removed"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}


echo json_encode($response);


