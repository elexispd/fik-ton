<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(isset($_POST["title"]) && isset($_POST["content"]) && isset($_FILES["thumbnail"]) && isset($_POST["video_link"])  && isset($_POST["genre"])  && isset($_POST["status"]) && isset($_POST["token"])) {

        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);        
        
        if($user != false) {
            $title = htmlspecialchars($_POST["title"]);
            $content = htmlspecialchars($_POST["content"]);
            $thumbnail = ($_FILES["thumbnail"]);
            $videoLink = htmlspecialchars($_POST["video_link"]);
            $author = $user[2];
            $genre = htmlspecialchars($_POST["genre"]);
            $status = htmlspecialchars($_POST["status"]);


            $trending = isset($_POST["trending"]) ? $_POST["trending"] : 0;
            $type = isset($_POST["type"]) ? $_POST["type"] : "All Types";
            
            $thumb = uploadThumbnail();   

            if(empty($title) && empty($content) && empty($thumb) && empty($video_link) && empty($genre) && empty($status)) {
                $response = ["status" => 0, "message" => "Compulsory fields can not be empty"];
            } else {
                $post_obj = new PostManager;

                if($status != 1 && $status != 0) {
                    $response = ["status" => 0, "message" => "Status can either be 1 0r 0"];
                } else {
                    if($post_obj->createPost($title, $content, $thumb, $videoLink, $author, $genre, $type, $trending, $status) ) {
                        if($status == 1) {
                            $response = ["status" => 1,
                            "message" => "Post created successfully"];
                        } else {
                            $response = ["status" => 1,
                            "message" => "Draft saved"];
                        }
                        http_response_code(201);
                    
                    } else {
                        http_response_code(500);
                        $response = ["status" => 0, "message" => "Post could not be created"];
                    }
                }
    
            }
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }

    } else {
        http_response_code(403);
        $response = ["status" => 0, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}

function uploadThumbnail() {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
 
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
            return "https://fikkton.com.ng/uploads/$newFileName";
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


