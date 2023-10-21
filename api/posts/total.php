<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $post_obj = new PostManager;
            $total = $post_obj->totalPosts();
            $response = ["status" => 201, "message" => "successfull", "data" => $total];
            http_response_code(201);
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


