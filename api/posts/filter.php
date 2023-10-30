
<?php 
header('Content-Type: application/json');

require_once(__DIR__ . "/../../models/CommentManager.php");
require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/PostManager.php");
require_once(__DIR__ . "/../../models/AccountManager.php");
require_once(__DIR__ . "/../../models/Auth.php");


$response = [];

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET["token"]) && isset($_GET["filter_by"]) && isset($_GET["genre"]) && isset($_GET["type"]) ) {
        $auth_obj = new Auth;
        $user = $auth_obj->authorize($_GET["token"]);
        if($user != false) {
            $user_obj = new AccountManager;
            $post_obj = new PostManager;
             
            $param1 = htmlspecialchars($_GET["filter_by"]);
            $genre = htmlspecialchars($_GET["genre"]);
            $type = htmlspecialchars($_GET["type"]);
            $filter = $post_obj->filter($param1, $type, $genre);
            $result = ($filter != false ) ? $filter : [] ;
            
            $response = ["status" => 1, "message" => "successful", "data" => $result];
            
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

