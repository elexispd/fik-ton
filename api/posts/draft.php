<?php 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
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
            $result = $post_obj->getDrafts();

            if($result != false) {
                http_response_code(201);
                $response = ["status" => 201,
                            "message" => "successful", 
                            "data" => $post_obj->getDrafts()];
            } elseif($result == false) {
                http_response_code(404);
                $response = ["status" => 102, "message" => "No content available"];
            }

        } else {
                http_response_code(401);
                $response = ["status" => 102, "message" => "Unauthorized user"];
            }
    } else {
        http_response_code(403);
        $response = ["status" => 105, "message" => "Invalid Parameter"];
    } 

    
    
 } else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


