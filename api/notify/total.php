<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $post_obj = new NotifyManager;
            $total = $post_obj->totalNotications();
            $response = ["status" => 1, "message" => "successfull", "data" => $total];
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    }  else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }    
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


