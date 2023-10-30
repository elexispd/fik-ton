<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"]) ) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        
        if(empty($email) || empty($password)) {
            $response = ["status" => 0, "message" => "All fields are required"];
        } else {
            $auth_obj = new Auth;
            $user  = $auth_obj->login($email, $password);
            
            if($user == false ) {
                $response = ["status" => 0, "message" => "Incorrect login details"];             
            } elseif($user == -1) {
                $response = ["status" => 0, "message" => "Account is inactive"]; 
            }
             else {
                $data = $user["id"].','.$password.','.$user["email"];
                $token = $auth_obj->encrypt($data);
                $response = ["status" => 1,
                     "message" => "Login successful",
                     "token" => $token,
                     "data" => [
                            "phone" => $user["phone"],
                            "gender" => $user["gender"],
                            "is_admin" => $user["is_admin"],
                            "status" => $user["status"],
                         ] ];
            }
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


