<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookmarkManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $book_obj = new BookmarkManager;

    if(isset($_GET['token'])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {          
            if($book_obj->Bookmarked($user[0]) != false) {
                $response = ["status" => 201,
                            "message" => "Success", 
                            "data"=> $book_obj->Bookmarked($user[0])
                            ];
                            
            } else {
                http_response_code(500);
                $response = ["status" => 100, "message" => "Bookmark was not successful"];
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


