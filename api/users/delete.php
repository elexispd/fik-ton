<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/AccountManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["user_id"])) {
        $admin_id = $_POST["admin_id"];
        $user_id = $_POST["user_id"];
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
                    } else {
                        $response = ["status" => 100, "message" => "User could not be deleted"];
                    }
                } else {
                    $response = ["status" => 401, "message" => "Unauthorized access"];
                }
            } else {
                $response = ["status" => 401, "message" => "Unauthorized access"];
            }

            
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


