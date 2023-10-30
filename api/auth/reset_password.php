<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["password"]) && isset($_POST["token"])) {
        $auth_obj = new Auth;

        $details = $auth_obj->decrypt($_POST["token"]);
        $email = explode(",", $details);
        $email = $email[0];
        $password = $_POST["password"];
        if(empty($password)) {
            $response = ["status" => 0, "message" => "Password field is required"];
        } else {
            $account_obj = new AccountManager;
            $user = $account_obj->getUserByEmail($email);
            if($account_obj->changePassword($email, $password) ) {

                $response = ["status" => 1,
                    "message" => "password was changed successfully. Login to continue"];
                    http_response_code(201);
            } else {
                http_response_code(500);
                $response = ["status" => 0, "message" => "Password could not be changed"];
            }
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


