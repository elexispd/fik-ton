<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["password"]) && isset($_POST["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);        
        
        if($user != false) {
            $email = htmlspecialchars($user[2]);
            $password = $_POST["password"];
            if(empty($password)) {
                $response = ["status" => 101, "message" => "Password field is required"];
            } else {
                $account_obj = new AccountManager;
                if($account_obj->changePassword($email, $password) ) {
                    $genToken = $user[0].",".$password.",".$email;
                    $newToken = $auth_obj->encrypt($genToken);
                    
                    $response = ["status" => 201,
                        "message" => "password was changed successfully", "token" => $newToken];
                        http_response_code(201);
                } else {
                    http_response_code(500);
                    $response = ["status" => 100, "message" => "Password could not be changed"];
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
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


