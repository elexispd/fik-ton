<?php 
require_once(__DIR__ . "/../../models/MailManager.php");
require_once(__DIR__ . "/../../models/Auth.php");

$response = [];

if(isset($_POST["email"]) ) {
    $mail_obj = new MailManager();

    $token = rand(1111, 9999);

    $email = $_POST["email"];
    $subject = "Confirm Your Registration: Unlock the Full Experience!";
    $message = "<h5>Hi, You are almost there. </h5> Your Verification code is <h4> <strong> $token </strong> </h4>";


    $msg = $mail_obj->sendMail("promisedeco24@gmail.com", $email, $subject, $message);
    if($msg == 1) {
        $auth = new Auth();
        if($auth->createToken($email, $token)) {
            $response = ["status" => 201, "message" => "token sent"];
        }
    } else {
        $response = ["status"=> 100, "message"=> $msg];
    }
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

echo json_encode($response);
