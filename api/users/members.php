<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $account_obj = new AccountManager;
    if($account_obj->getUsers() != false) {
        $response = ["status" => 201,
                     "message" => "successful", 
                     "data" => $account_obj->getUsers()];
    } else {
        $response = ["status" => 100, "message" => "No data to return"];
    }
    
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);



