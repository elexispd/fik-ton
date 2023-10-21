<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);        
        
        if($user[0] != false) {
            $email = htmlspecialchars($_POST["email"]);
            $password = $_POST["password"];
            if(empty($email) || empty($password)) {
                $response = ["status" => 101, "message" => "All fields are required"];
            } else {
                $account_obj = new AccountManager;
                if($account_obj->changePassword($email, $password) ) {
                    $response = ["status" => 201,
                        "message" => "password was changed successfully"];
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


