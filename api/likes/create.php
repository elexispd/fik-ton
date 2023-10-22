<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["token"]) && isset($_POST["post_id"]) ) {

        $post_id = htmlspecialchars($_POST["post_id"]);    
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            if(empty($post_id) ) {
                $response = ["status" => 101, "message" => "Compulsory field can not be empty"];
            } else {
                $like_obj = new LikeManager;
                if($like_obj->createLike($user[0], $post_id) ) {
                    $response = ["status" => 201,
                        "message" => "success"];
                        http_response_code(201);
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "could not like"];
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


