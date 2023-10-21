<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];



if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $book_obj = new BookmarkManager;

    if(isset($_GET['token']) && isset($_GET["post_id"])) {
        $post_id = $_GET['post_id'];
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            if(empty($post_id)) {
                $response = ["status" => 100, "message" => "PostID parameter is required"];
            } else {
                if($book_obj->isBookmarked($user[0], $post_id) != false) {
                    $response = ["status" => 201,
                                "message" => "Bookmarked", 
                                ];
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Bookmark was not successful"];
                }
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 100, "message" => "Invalid Parameter"];
    }

   
    
 } else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}




echo json_encode($response);


