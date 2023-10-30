<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/Auth.php");
require_once(__DIR__ . "/../../models/MailManager.php");

$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["email"]) ) {
        $email = $_POST["email"];

        if(empty($email)) {
            $response = ["status" => 0, "message" => "Email is required"];
        } else {

            if(validateEmail($email) === false) {
                $response = ["status"=> 0, "message"=> "Invalid Email Address"];
            } else {
                $auth_obj = new Auth;
                if($auth_obj->isUserExists($email)) { 
                    $mailResponse = sendMail_createToken($email, $auth_obj);
                    if($mailResponse) {
                        $data = $email.","."1";
                        $token = $auth_obj->encrypt($data);
                        $response = ["status" => 1,
                        "message" => "Password reset successful. A verification code was sent to your email", "token" => $token];
                    } else {
                        $response = ["status" => 0, "message" => $mailResponse];
                    }
                } else {
                    $response = ["status"=> 0, "message"=> "Email does not belong to any user"];
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


function sendMail_createToken($email, $auth_obj) {
    $mail_obj = new MailManager();

    $token = rand(1111, 9999);

    $email = $_POST["email"];
    $subject = "Password Reset Request";
    $message = "<h5>Hi, You are almost there. </h5> Your email verification cod code is <h4> <strong> $token </strong> </h4> <p style='color:lightgrey; font-size:14px;'> please note that this code will expire after 5 Minutes";


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


