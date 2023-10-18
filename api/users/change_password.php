<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"])) {
        $email = htmlspecialchars($_POST["email"]);
        $password = $_POST["password"];
        if(empty($email) || empty($password)) {
            $response = ["status" => 101, "message" => "All fields are required"];
        } else {
            $account_obj = new AccountManager;
            if($account_obj->changePassword($email, $password) ) {
                $response = ["status" => 201,
                     "message" => "password was changed successfully"];
            } else {
                $response = ["status" => 100, "message" => "Password could not be changed"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);

