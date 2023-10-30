<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PinManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["pin_id"]) && isset($_POST["token"]) ) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            $id = $_POST["pin_id"];
            if(empty($id)) {
                $response = ["status" => 0, "message" => "ID field is required"];
            } else {
                $pin_obj = new PinManager;
                if($pin_obj->unpin($id) == true ) {
                    $response = ["status" => 1,
                        "message" => "content unpinned successfully"];
                } else {
                    $response = ["status" => 0, "message" => "Content could not be unpinned"];
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


