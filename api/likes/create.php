<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");
require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["token"]) && isset($_POST["post_id"]) ) {

        $post_id = htmlspecialchars($_POST["post_id"]);    
        $auth_obj = new Auth;
        $post_obj = new PostManager;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            if(empty($post_id) ) {
                $response = ["status" => 0, "message" => "Compulsory field can not be empty"];
            } else {
                $like_obj = new LikeManager;
                if($like_obj->createLike($user[0], $post_id) ) {
                    $post_obj->increasePostLike($post_id);
                    $response = ["status" => 1,
                        "message" => "success"];
                        http_response_code(201);
                } else {
                    http_response_code(500);
                    $response = ["status" => 0, "message" => "could not like"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 0, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}




echo json_encode($response);


