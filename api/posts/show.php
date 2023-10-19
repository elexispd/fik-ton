<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["id"])) {
        $id = htmlspecialchars($_GET["id"]);
        if(empty($id)) {
            $response = ["status" => 101, "message" => "ID must not be empty"];
        } else {
            $post_obj = new PostManager;
            if($account_obj->getPost($id) != false) {
                $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $post_obj->getPost($id)];
            } else {
                $response = ["status" => 100, "message" => "Post does not exist"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


