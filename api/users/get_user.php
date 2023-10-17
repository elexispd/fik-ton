<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"])) {
        $email = htmlspecialchars($_POST["email"]);
        if(empty($email)) {
            $response = ["status" => 101, "message" => "Email must not be empty"];
        } else {
            $account_obj = new AccountManager;
            if($account_obj->getUser($email) != false) {
                $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $account_obj->getUser($email)];
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


