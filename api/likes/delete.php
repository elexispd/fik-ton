<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"]) && isset($_POST["token"]) ) {
        $post_id = $_POST["post_id"];
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($post_id)) {
                $response = ["status" => 101, "message" => "PostID field is required"];
            } else {
                $post_obj = new LikeManager;
                if($post_obj->deleteLike($user[0], $post_id) ) {
                    $response = ["status" => 201,
                        "message" => "Like removed successfully"];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Like could not be removed"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


