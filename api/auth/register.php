<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/Auth.php");
require_once(__DIR__ . "/../../models/MailManager.php");

$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["gender"]) && isset($_POST["phone"])   ) {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $topic = $_POST["subscribed_users"];

        if(empty($email) || empty($password) || empty($gender) || empty($phone)) {
            $response = ["status" => 0, "message" => "All fields are required"];
        } else {

            if(validateEmail($email) === false) {
                $response = ["status"=> 0, "message"=> "Invalid Email Address"];
            } elseif(validatePhone($phone) === false) {
                $response = ["status"=> 0, "message"=> "Invalid phone number format"];
            } elseif(validateGender($gender) === false) {
                $response = ["status"=> 0, "message"=> "Unidentified gender"];
            } else {
                $auth_obj = new Auth;
                if($auth_obj->isUserExists($email)) { 
                    $response = ["status"=> 0, "message"=> "User already exist"];
                } else {

                    if($auth_obj->registerUser($email, $password, $gender, $phone, $topic) ) {
                        $mailResponse = sendMail_createToken($email, $auth_obj);
                        if($mailResponse) {
                            $data = $email.","."1";
                            $token = $auth_obj->encrypt($data);
                            $response = ["status" => 1,
                            "message" => "registration successful. A verification code was sent to your email", "token" => $token];
                        } else {
                            $response = ["status" => 0, "message" => $mailResponse];
                        }
                        
                    } else {
                        $response = ["status" => 0, "message" => "Registration failed. Something with wrong"];
                    }
                }
            }
  
        }
    } else {
        $response = ["status" => 0, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 0, "message" => "Invalid Request Method"];
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
    // Allow either a digit or + at the start, followed by digits and optional hyphens
    // Ensure it doesn't end with + or -
    if (preg_match('/^([0-9]\d*|\+\d+)(?:-\d+)*$/', $phone)) {
        return true; // Valid phone number
    } else {
        return false; // Invalid phone number
    }
}

  function sendMail_createToken($email, $auth_obj) {
    $mail_obj = new MailManager();

    $token = rand(1111, 9999);

    $email = $_POST["email"];
    $subject = "Confirm Your Registration: Unlock the Full Experience!";
    $message = "<h5>Hi, You are almost there. </h5> Your Verification code is <h4> <strong> $token </strong> </h4> <p style='color:lightgrey; font-size:14px;'> please note that this code will expire after 5 Minutes";


    $msg = $mail_obj->sendMail("support@fikkon.com.ng", $email, $subject, $message);
    if($msg == 1) {
        if($auth_obj->createToken($email, $token)) {
            return true;
        }
    } else {
        return $msg;
    }
  }
  

echo json_encode($response);


