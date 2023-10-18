<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/NotifyManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["post_id"])) {
        $id = $_POST["post_id"];
        if(empty($id)) {
            $response = ["status" => 101, "message" => "ID field is required"];
        } else {
            $post_obj = new NotifyManager;
            if($post_obj->deleteNotification($id) ) {
                $response = ["status" => 201,
                     "message" => "Notification deleted successfully"];
            } else {
                $response = ["status" => 100, "message" => "Notication could not be deleted"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameter"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}


echo json_encode($response);


