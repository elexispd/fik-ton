<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"]) && isset($_POST["user_id"]) ) {
        $post_id = $_POST["post_id"];
        $user_id = $_POST["user_id"];
        if(empty($user_id) || empty($post_id)) {
            $response = ["status" => 101, "message" => "All fields are required"];
        } else {
            $post_obj = new LikeManager;
            if($post_obj->deleteLike($user_id, $post_id) ) {
                $response = ["status" => 201,
                     "message" => "Like removed successfully"];
            } else {
                $response = ["status" => 100, "message" => "Like could not be removed"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


