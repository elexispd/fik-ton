<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["id"])) {
        $id = $_POST["id"];
        if(empty($id)) {
            $response = ["status" => 101, "message" => "ID field is required"];
        } else {
            $post_obj = new PostManager;
            if($post_obj->deletePost($id) ) {
                $response = ["status" => 201,
                     "message" => "post deleted successfully"];
            } else {
                $response = ["status" => 100, "message" => "Post could not be deleted"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


