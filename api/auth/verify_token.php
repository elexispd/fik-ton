<?php 
require_once(__DIR__ . "/../../models/MailManager.php");
require_once(__DIR__ . "/../../models/Auth.php");

$response = [];

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if(isset($_POST["email"]) && isset($_POST["token"]) ) {

        $email = $_POST["email"];
        $token = $_POST["token"];

        if(!empty($email) || !empty($token)) {
            $auth_obj = new Auth();
            if($auth_obj->verifyToken($email, $token) === 1) {
                $response = ["status" => 201, "message" => "token verified successfully", "data" => $email];
            } elseif($auth_obj->verifyToken($email, $token) === 0 ) {
                $response = ["status" => 404, "message" => "Invalid verification code"];
            } else {
                $response = ["status" => 101, "message" => "Code has expired"];
            }
        } else {
            $response = ["status"=> 100, "message"=> "All fields are required"];
        }
    } else {
        $response = ["status" => 105, "message" => "Parameter is not completely passed"];
    }
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);
