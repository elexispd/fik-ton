<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $book_obj = new BookmarkManager;
    $user_id = $_GET['user_id'];

    if($book_obj->Bookmarked($user_id) != false) {
        $response = ["status" => 201,
                     "message" => "Success", 
                     "data"=> $book_obj->Bookmarked($user_id)
                    ];
                     
    } else {
        $response = ["status" => 100, "message" => "Bookmark was not successful"];
    }
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


