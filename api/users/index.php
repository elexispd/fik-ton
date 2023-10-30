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
            if($account_obj->getAllUsers() != false) {
                $response = ["status" => 1,
                            "message" => "successful", 
                            "data" => $account_obj->getAllUsers()];
                            http_response_code(201);
            } else {
                http_response_code(200);
                $response = ["status" => 0, "message" => "No data to return"];
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
 } else {
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);


