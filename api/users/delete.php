<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["user_id"]) && isset($_POST["token"])) {
        $admin_id = $_POST["admin_id"];
        
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);        
        
        if($user[0] != false) {
            if(empty($is_admin) || empty($user_id)) {
                $response = ["status" => 101, "message" => "Fields are required"];
            } else {
                $account_obj = new AccountManager;

                $admin = $account_obj->getUserByID($admin_id);
                $user = $account_obj->getUserByID($user_id);
                if($admin != false && $user != false) {
                    if($admin["is_admin"] == 2 ) {
                        if($account_obj->deleteUser($user_id) ) {
                            $response = ["status" => 201,
                                "message" => "User deleted successfully"];
                                http_response_code(201);
                        } else {
                            $response = ["status" => 100, "message" => "User could not be deleted"];
                            http_response_code(500);
                        }
                    } else {
                        $response = ["status" => 401, "message" => "Unauthorized access"];
                    }
                } else {
                    http_response_code(401);
                    $response = ["status" => 401, "message" => "Unauthorized access"];
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


