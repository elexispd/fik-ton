<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/BookmarkManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {

    $auth_obj = new Auth;
    $user = $auth_obj->authorize($_GET["token"]);
    if($user != false) {
        $book_obj = new BookmarkManager;
        $total = $book_obj->totalBookmark();
        $response = ["status" => 1, "message" => "successfull", "data" => $total];
    } else {
        http_response_code(401);
        $response = ["status" => 0, "message" => "Unauthorized user"];
    }
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


