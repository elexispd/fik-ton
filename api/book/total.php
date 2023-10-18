<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookmarkManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $book_obj = new BookmarkManager;
    $total = $book_obj->totalBookmark();
    $response = ["status" => 201, "message" => "successfull", "data" => $total];
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


