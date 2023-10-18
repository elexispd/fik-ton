<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["gender"]) && isset($_POST["phone"])   ) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];

        if(empty($email) || empty($password) || empty($gender) || empty($phone)) {
            $response = ["status" => 101, "message" => "All fields are required"];
        } else {

            if(validateEmail($email) === false) {
                $response = ["status"=> 100, "message"=> "Invalid Email Address"];
            } elseif(validatePhone($phone) === false) {
                $response = ["status"=> 100, "message"=> "Invalid phone number format"];
            } elseif(validateGender($gender) === false) {
                $response = ["status"=> 100, "message"=> "Unidentified gender"];
            } else {
                $auth_obj = new Auth;
                if($auth_obj->isUserExists($email)) { 
                    $response = ["status"=> 100, "message"=> "User already exist"];
                } else {

                    if($auth_obj->registerUser($email, $password, $gender, $phone) ) {
                        $response = ["status" => 201,
                            "message" => "registration successful. A verification code was sent to your email"];
                    } else {
                        $response = ["status" => 100, "message" => "Registration failed. Something with wrong"];
                    }
                }
            }
  
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


function validateEmail($email) {
    $email = trim($email);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

function validateGender($gender) {
    $gender = ucwords(trim($gender));

    if($gender === "Male" || $gender == "Female") {
        return true;
    } else {
        return false;
    }

}

function validatePhone($phone) { 

    if (preg_match('/^((\+\d{1,15})|(^[1-9]\d*))[-\d]*\d$/', $phone)) {
      return true; // Valid phone number
    } else {
      return false; // Invalid phone number
    }
  }
  

echo json_encode($response);


