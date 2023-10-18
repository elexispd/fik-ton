<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");






class PostManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createPost($title, $content, $thumbnail, $videoLink, $author, $genre, $trending) {
        $sql = "INSERT INTO posts (title, content, thumbnail, video_link, author, genre, is_trending, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$title, $content, $thumbnail, $videoLink, $author,  $genre, $trending, 1, $date]);         
            if($stmt->rowCount() > 0) {
                return true; // Registration successful
            } else {
                return false; // Registration failed
            }
        } catch (PDOException $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }
    }

    public function getPosts() {
        $sql = "SELECT * FROM posts";
        try {
            $stmt = $this->dbHandler->run($sql);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function getPost($id) {
        $sql = "SELECT * FROM posts WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$id]);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetch();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function deletePost($id) {
        $sql = "DELETE FROM posts WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$id]);
            if($stmt->rowCount() > 0 ) {
                return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function updatePost($title, $content, $thumbnail, $videoLink, $author,  $genre, $trending, $id) {
        $date = time();
        $sql = "UPDATE posts SET title = ?, content = ?, thumbnail = ?, video_link = ?, author = ?,  genre = ?, is_trending = ?, updated_at = ?  WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$title, $content, $thumbnail, $videoLink, $author,  $genre, $trending, $date, $id]);
            if($stmt) {
               return true;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }    
    }

    public function getPostByGenre($genre) {
        $sql = "SELECT * FROM posts WHERE genre = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$genre]);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function getRecentPosts() {
        $sql = "SELECT * FROM posts ORDER BY create_at DESC";
        try {
            $stmt = $this->dbHandler->run($sql);
            if($stmt->rowCount() > 0 ) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
        
    }

    public function totalPosts() {
        $sql = "SELECT * FROM posts";
        try {
            $stmt = $this->dbHandler->run($sql);
            return $stmt->rewCount();
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

   



} 

/*  unit test  */

$test = new PostManager();
//  $output = $test->getPost(1);
//  print_r($output);
//$output = $test->createPost("Hello world", "lorem PostManager PostManager PostManager ", 'dfjkhjh23h4k2', "video", "Dfads", "action", 2);
//$output = $test->updatePost("Hello update", "lorem PostManager PostManager PostManager ", 'dfjkhjh23h4k2', "video", "Dfads", 2);
// $output = $test->deletePost(1);
//echo $output ? "true" : "false";
