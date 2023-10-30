<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["user_id"]) && isset($_POST["token"])) {
        
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);  
        $user_id = $_POST["user_id"];
        
        if($user != false) {
            if(empty($user_id)) {
                $response = ["status" => 0, "message" => "User ID field is required"];
            } else {
                $account_obj = new AccountManager;

                $admin = $account_obj->getUserByID($user[0]);
                $user = $account_obj->getUserByID($user_id);
                if($admin != false && $user != false) {
                    if($admin["is_admin"] == 1 ) {
                        if($account_obj->deleteUser($user_id) ) {
                            $response = ["status" => 1,
                                "message" => "User deleted successfully"];
                                http_response_code(201);
                        } else {
                            $response = ["status" => 0, "message" => "User could not be deleted"];
                            http_response_code(500);
                        }
                    } else {
                        $response = ["status" => 0, "message" => "Unauthorized access"];
                    }
                } else {
                    http_response_code(401);
                    $response = ["status" => 0, "message" => "User dos not exist"];
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
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}


echo json_encode($response);


