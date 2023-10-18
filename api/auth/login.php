<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"]) ) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        if(empty($email) || empty($password)) {
            $response = ["status" => 101, "message" => "All fields are required"];
        } else {
            $auth_obj = new Auth;
            if($auth_obj->login($email, $password) ) {
                $response = ["status" => 201,
                     "message" => "Login successful"];
            } else {
                $response = ["status" => 100, "message" => "Incorrect login details"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


