<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["email"]) || isset($_GET["user_id"])) {
        
        $email = isset($_GET["email"]) ? htmlspecialchars($_GET["email"]) : null;
        $user_id = isset($_GET["user_id"]) ? htmlspecialchars($_GET["user_id"]) : null;


        if(empty($email) && empty($user_id)) {
            $response = ["status" => 101, "message" => "Email must not be empty"];
        } else {
            $account_obj = new AccountManager;
            if($account_obj->getUser($email, $user_id) != false) {
                $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $account_obj->getUser($email, $user_id)];
            } else {
                $response = ["status" => 100, "message" => "User does not exist"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


