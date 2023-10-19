<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $comment_obj = new CommentManager;
    if(isset($_POST["post_id"])) {
        $id = htmlspecialchars($_GET["post_id"]);
        if(empty($id)) {
            $response = ["status" => 101, "message" => "Post ID must not be empty"];
        } else {
            if($notify_obj->showComments($id) != false) {
                $response = ["status" => 201,
                            "message" => "successful", 
                            "data" => $comment_obj->showComments($id)];
            } else {
                $response = ["status" => 100, "message" => "No data to return"];
            }
        }
    } 
    else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
    
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


