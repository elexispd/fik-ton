<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $account_obj = new AccountManager;
    $total = $account_obj->totalUsers();
    $response = ["status" => 201, "message" => "successfull", "data" => $total];
    
 } else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);


