<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PinManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {


        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);        
        
        if($user != false) {
            
            $content = isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : "";
            $thumbnail = isset($_FILES["thumbnail"]) ? ($_FILES["thumbnail"]) : "";
            $videoLink = isset($_POST["video_link"]) ? htmlspecialchars($_POST["video_link"]) : "";
            $author = $user[2];
            
            if(!empty($thumbnail)) {
                $thumb = uploadThumbnail(); 
            } else {
                $thumb = "";
            }
              

            if(empty($content) && empty($thumb) && empty($video_link) ) {
                $response = ["status" => 0, "message" => "All fields can not be empty"];
            } else {
                $pin_obj = new PinManager;

                if($pin_obj->createPin($content, $thumb, $videoLink, $author) ) {
                    $response = ["status" => 1,
                    "message" => "Content was pinned successfully"];
                    http_response_code(201);
                } else {
                    http_response_code(500);
                    $response = ["status" => 0, "message" => "Content could not be created"];
                }
               
    
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }


      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

function uploadThumbnail() {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/pinned/';
 
    // Make sure the directory exists; if not, create it
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            // Directory creation failed
            return 0;
        }
    }

    if ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $tempPath = $_FILES['thumbnail']['tmp_name'];
        $originalFileName = $_FILES['thumbnail']['name'];

        // Generate a unique filename using the user's username and current timestamp
        $timestamp = time();
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $newFileName = $timestamp . '.' . $fileExtension;
        $filePath = $uploadDir . $newFileName;

        if (move_uploaded_file($tempPath, $uploadDir . $newFileName)) {
            return "https://fikkton.com.ng/uploads/pinned/$newFileName";
        } else {
            // Log the error for debugging
            error_log("Failed to move uploaded file: $tempPath to $filePath");
            return 0;
        }

    } else {
        // Log the error for debugging
        error_log("File upload error: " . $_FILES['thumbnail']['error']);
        return 0;
    }
}



echo json_encode($response);


