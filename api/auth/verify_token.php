<?php 
require_once(__DIR__ . "/../../models/MailManager.php");
require_once(__DIR__ . "/../../models/Auth.php");

$response = [];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["token"]) &&  isset($_POST["code"]) ) {
        $token = $_POST["token"];
        $code = $_POST["code"];
        $auth_obj = new Auth;
        $user = $auth_obj->decrypt($token); 
        $user = explode(",", $user);
        
        if(!empty($code) ) {
            $auth_obj = new Auth();
            if($auth_obj->verifyToken($user[0], $code) === 1) {
                $response = ["status" => 1, "message" => "token verified successfully. Login to continue", "token" => $token];
            } elseif($auth_obj->verifyToken($user[0], $code) === 0 ) {
                $response = ["status" => 0, "message" => "Incorrect Verification Code"];
            } else {
                $response = ["status" => 0, "message" => "Code has expired"];
            }
        } else {
            $response = ["status"=> 0, "message"=> "All fields are required"];
        }
    } else {
        $response = ["status" => 0, "message" => "Parameter is not completely passed"];
    }
} else {
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

echo json_encode($response);
