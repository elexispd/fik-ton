<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["title"]) && isset($_POST["content"]) && isset($_FILES["thumbnail"]) && isset($_POST["video_link"]) && isset($_POST["genre"]) && isset($_POST["token"]) && isset($_POST["post_id"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_POST["token"]);
        if($user != false) {
            $title = htmlspecialchars($_POST["title"]);
            $content = htmlspecialchars($_POST["content"]);
            $thumbnail = ($_FILES["thumbnail"]);
            $videoLink = htmlspecialchars($_POST["video_link"]);
            $author = $data[2];
            $genre = htmlspecialchars($_POST["genre"]);
            $post_id = htmlspecialchars($_POST["post_id"]);


            $trending = isset($_POST["trending"]) ? $_POST["trending"] : 0;
            
            $thumb = uploadThumbnail();   

            if(empty($title) && empty($content) && empty($thumb) && empty($video_link) && empty($genre) && empty($post_id)) {
                $response = ["status" => 101, "message" => "Compulsory fields can not be empty"];
            } else {
                $post_obj = new PostManager;

                if($status != 1 || $status != 0) {
                    $response = ["status" => 102, "message" => "Status can either be 1 0r 0"];
                } else {
                    if($post_obj->updatePost($title, $content, $thumb, $videoLink, $author, $genre, $trending, $post_id) ) {
                        $response = ["status" => 201,
                            "message" => "Post updated successfully"];
                    } else {
                        $response = ["status" => 100, "message" => "Post could not be update"];
                    }
                }
    
            }
        } else {
            http_response_code(401);
            $response = ["status" => 102, "message" => "Unauthorized user"];
        }
    } else {
        http_response_code(403);
        $response = ["status" => 102, "message" => "Invalid Parameters"];
    }
      
} else {
    http_response_code(405);
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

function uploadThumbnail() {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/'; // Set your desired upload directory
    
    if ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        
         $tempPath = $_FILES['thumbnail']['tmp_name'];
        $originalFileName = $_FILES['thumbnail']['name'];
        

        // Generate a unique filename using the user's username and current timestamp
        $timestamp = time();
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $newFileName = $timestamp . '.' . $fileExtension;
        
        $filePath = $uploadDir . $newFileName;

    
        if (move_uploaded_file($tempPath, $uploadDir . $newFileName)) {
            return ($uploadDir . $newFileName);
        } else {
            return 0;
        }

    } else {
        return 0;
    }
}


echo json_encode($response);


