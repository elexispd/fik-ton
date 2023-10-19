<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"]) && isset($_POST["user_id"]) ) {
        $user_id = htmlspecialchars($_POST["user_id"]);
        $post_id = htmlspecialchars($_POST["post_id"]);       

        if(empty($user_id) && empty($post_id) ) {
            $response = ["status" => 101, "message" => "Compulsory fields can not be empty"];
        } else {
            $comment_obj = new CommentManager;
            if($comment_obj->createComment($user_id, $post_id) ) {
                $response = ["status" => 201,
                     "message" => "Comment added"];
            } else {
                $response = ["status" => 100, "message" => "Something went wrong"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameters"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}




echo json_encode($response);


