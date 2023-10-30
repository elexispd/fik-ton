<?php 
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $user_obj = new AccountManager;
            if($user_obj->getUsersByRegistrationMonth() != false) {
                $response = ["status" => 1,
                            "message" => "successful", 
                            "data" => $user_obj->getUsersByRegistrationMonth() ];
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
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);





