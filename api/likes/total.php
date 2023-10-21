<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/LikeManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_POST["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $like_obj = new LikeManager;
            $total = $like_obj->totalLikes();
            $response = ["status" => 201, "message" => "successfull", "data" => $total];
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
    
    
 } else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


