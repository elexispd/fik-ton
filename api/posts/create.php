<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/PostManager.php");


$response = [];


if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(isset($_POST["title"]) && isset($_POST["content"]) && isset($_FILES["thumbnail"]) && isset($_POST["video_link"]) && isset($_POST["genre"]) && isset($_POST["author"])) {
        $title = htmlspecialchars($_POST["title"]);
        $content = htmlspecialchars($_POST["content"]);
        $thumbnail = ($_FILES["thumbnail"]);
        $videoLink = htmlspecialchars($_POST["video_link"]);
        $author = htmlspecialchars($_POST["author"]);
        $genre = htmlspecialchars($_POST["genre"]);


        $trending = isset($_POST["trending"]) ? $_POST["trending"] : 0;
        
        $thumb = uploadThumbnail();   

        if(empty($title) && empty($content) && empty($thumb) && empty($video_link) && empty($genre)) {
            $response = ["status" => 101, "message" => "Compulsory fields can not be empty"];
        } else {
            $post_obj = new PostManager;
            if($post_obj->createPost($title, $content, $thumb, $videoLink, $author, $genre, $trending) ) {
                $response = ["status" => 201,
                     "message" => "post created successfully"];
            } else {
                $response = ["status" => 100, "message" => "Post could not be deleted"];
            }
        }
    } else {
        $response = ["status" => 102, "message" => "Invalid Parameters"];
    }
      
} else {
    $response = ["status" => 105, "message" => "Invalid Request Method"];
}

function uploadThumbnail() {
    $uploadDir = $_SERVER['DOCUMENT_ROOT'].'/uploads/'; // Set your desired upload directory

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
            return ($uploadDir . $newFileName);
        } else {
            return 0;
        }

    } else {
        return 0;
    }
}


echo json_encode($response);


