<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);        
        
        if($user != false) {
            $account_obj = new AccountManager;
            $total = $account_obj->totalUsers();
            $response = ["status" => 1, "message" => "successfull", "data" => $total];
            http_response_code(202);
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


