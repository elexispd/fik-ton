<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["user_id"]) && isset($_GET["token"])  ) {
        
        $id = isset($_GET["user_id"]) ? htmlspecialchars($_GET["user_id"]) : null;

            $auth_obj = new Auth;
            $user = $auth_obj->authorize($_GET["token"]);        
            
            if($user != false) {
                if(empty($id) ) {
                    $response = ["status" => 0, "message" => "UserID must not be empty"];
                } else {
                    $account_obj = new AccountManager;
                    if($account_obj->getUser($id, $id) != false) {
                        $response = ["status" => 1,
                            "message" => "successful", 
                            "data" => $account_obj->getUser($id, $id)];
                            http_response_code(201);
                    } else {
                        $response = ["status" => 0, "message" => "User does not exist"];
                        http_response_code(200);
                    }
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


