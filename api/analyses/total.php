<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");
require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"])) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $comment_obj = new CommentManager;
            $user_obj = new AccountManager;
            $post_obj = new PostManager;
            // $user_obj = new AccountManager;
            
            
            $totalC = $comment_obj->total();
            $totalU= $user_obj->totalUsers();
            $totalP= $post_obj->totalPosts();
            $totalV= $post_obj->totalViews();
            $usersAnalysis= $user_obj->getUsersTotalByRegistrationMonth();
            $viewsAnalysis= $post_obj->getViewsTotalByRegistrationMonth();

            
            $details = [
                    "comments"=> $totalC,
                    "users" => $totalU,
                    "posts" => $totalP,
                    "views" => $totalV,
                    "usersAnalysis" => $usersAnalysis,
                    'viewsAnalysis' => $viewsAnalysis
                    ];
            $response = ["status" => 1, "message" => "successfull", "data" => $details];
            
        } else {
            http_response_code(401);
            $response = ["status" => 0, "message" => "Unauthorized user"];
        }
    
    } else {
        $response = ["status" => 0, "message" => "Invalid Parameter" ];
    }
    
 } else {
    http_response_code(405);
    $response = ["status" => 0, "message" => "Invalid Request Method"];
}



echo json_encode($response);