<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["email"]) && isset($_GET["token"])  ) {
        
        $email = isset($_GET["email"]) ? htmlspecialchars($_GET["email"]) : null;

            $auth_obj = new Auth;
            $user = $auth_obj->authorize($_GET["token"]);        
            
            if($user[0] != false) {
                if(empty($email) ) {
                    $response = ["status" => 101, "message" => "Email/UserID must not be empty"];
                } else {
                    $account_obj = new AccountManager;
                    if($account_obj->getUser($email, $user_id) != false) {
                        $response = ["status" => 201,
                            "message" => "successful", 
                            "data" => $account_obj->getUser($email, $user_id)];
                            http_response_code(201);
                    } else {
                        $response = ["status" => 100, "message" => "User does not exist"];
                        http_response_code(204);
                    }
                }
            } else {
                http_response_code(401);
                $response = ["status" => 102, "message" => "Unauthorized user"];
            }
    } else {
        http_response_code(403);
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }   
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


