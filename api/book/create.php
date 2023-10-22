<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookmarkManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST["post_id"]) && isset($_POST["token"]) )  {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            $post_id = $_POST["post_id"];
            if(empty($post_id)) {
                $response = ["status" => 101, "message" => "All fields are required"];
            } else {
                $post_obj = new BookmarkManager;
                if($post_obj->createBookmark($user[0], $post_id) ) {
                    $response = ["status" => 201,
                        "message" => "Post bookmarked successfully"];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Post removed from bookmark"];
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
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


