<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $id = htmlspecialchars($_GET["post_id"]);
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($id)) {
                $response = ["status" => 0, "message" => "ID must not be empty"];
            } else {
                $post_obj = new PostManager;
                if($post_obj->getPost($user[0], $id) != false) {
                   $post_obj->updatePostView($id, $user[0]);
                    $response = ["status" => 1,
                        "message" => "successful", 
                        "data" => $post_obj->getPost($user[0], $id)];
                        http_response_code(201);
                } else {
                    http_response_code(200);
                    $response = ["status" => 0, "message" => "Post does not exist"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}


echo json_encode($response);


