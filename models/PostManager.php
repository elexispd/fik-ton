<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
//Confirm if file is local or Public and add the right path

require_once(__DIR__ . "/../vendor/autoload.php");

require_once(__DIR__ . "/InitDB.php");
require_once(__DIR__ . "/PinManager.php");






class PostManager
{

    private $dbHandler;
    private $system_message;


    function __construct()
    {
        $this->dbHandler = new InitDB();
    }

    public function createPost($title, $content, $thumbnail, $videoLink, $author, $genre, $type, $trending,  $status) {
        $sql = "INSERT INTO posts (title, content, thumbnail, video_link, author, genre, type, is_trending, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";       
        try {
            $date = time();
            $stmt = $this->dbHandler->run($sql, [$title, $content, $thumbnail, $videoLink, $author,  $genre, $type, $trending, $status, $date]);         
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
    
    private function genres() {
        $genres = ["Action", "Adventure", "Animation", "Comedy", "Crime", "Documentary", "Family", "Romance"];
        return $genres;
    }
    
    private function type() {
        $type = ["All Types", "Movies", "Tv Series", "Drama"];
        return $type;
    }
    
    private function filter_by() {
        $sort = ["A-Z", "popular", "oldest", "recent", "Z-A"];
        return $sort;
    }
    
    
    public function getPosts($user_id) {
        //get all pinned contents to be displayed along with posts
        $pin_obj = new PinManager;
        $pinned = $pin_obj->listPinned();
        
        $sql = "SELECT posts.*, 
                      IF(bookmark.user_id IS NOT NULL, 1, 0) AS is_booked
                FROM posts
                LEFT JOIN bookmark ON posts.id = bookmark.post_id AND bookmark.user_id = ?
                WHERE posts.status = ?";
    
        try {
            $stmt = $this->dbHandler->run($sql, [$user_id, 1]);
            if ($stmt->rowCount() > 0) {
                $posts = $stmt->fetchAll();
                
                $posts = ($posts != false) ? $posts : [];
                $filter = ($this->filter_by() != false) ? $this->filter_by() : [];
                $genre = ($this->genres() != false) ? $this->genres() : [];
                $pinned = ($pinned != false) ? $pinned : [];
                
                // Combine with genres and filter_by
                $result = [
                    'posts' => $posts,
                    'genres' => $genre,
                    "type" => $this->type(),
                    'filter_by' => $filter,
                    "pinned" => $pinned
                ];
    
                return $result;
            } else {
                return 0;
            }
        } catch (\Throwable $e) {
            return "Database Error: " . $e->getMessage();
        }
    }

    public function getDrafts() {
        //get all pinned contents to be displayed along with posts
        $pin_obj = new PinManager;
        $pinned = $pin_obj->listPinned();
        
        $sql = "SELECT * FROM posts WHERE status = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [0]);
            if($stmt->rowCount() > 0 ) {
                $posts = $stmt->fetchAll();
                
                $posts = ($posts != false) ? $posts : [];
                $filter = ($this->filter_by() != false) ? $this->filter_by() : [];
                $genre = ($this->genres() != false) ? $this->genres() : [];
                $pinned = ($pinned != false) ? $pinned : [];
                
                 // Combine with genres and filter_by
                $result = [
                    'posts' => $posts,
                    'genres' => $genre,
                    "type" => $this->type(),
                    'filter_by' => $this->filter_by(),
                    "pinned" => $pinned
                ];
                
                return $result;
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }

    public function getPost($user_id, $id) {
        $sql = "SELECT posts.*, 
                   IF(bookmark.user_id IS NOT NULL, 1, 0) AS is_booked,
                   IF(likes.user_id IS NOT NULL, 1, 0) AS is_liked
                FROM posts
                LEFT JOIN bookmark ON posts.id = bookmark.post_id AND bookmark.user_id = ?
                LEFT JOIN likes ON posts.id = likes.post_id AND likes.user_id = ?
                WHERE posts.id = ?;
            ";
        try {
            $stmt = $this->dbHandler->run($sql, [$user_id, $user_id, $id]);
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

    public function updatePost($title, $content, $thumbnail, $videoLink, $author,  $genre, $type, $trending, $id) {
        $date = time();
        $sql = "UPDATE posts SET title = ?, content = ?, thumbnail = ?, video_link = ?, author = ?,  genre = ?, type = ?, is_trending = ?, updated_at = ?  WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$title, $content, $thumbnail, $videoLink, $author,  $genre, $type, $trending, $date, $id]);
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
    
    
    
    public function filter($param1, $type, $genre) {
        // Base SQL query
        $sql = "SELECT * FROM posts WHERE type = ? AND genre = ? ";
    
        // Append ORDER BY clause based on $param1
        switch ($param1) { 
            case 'A-Z':  
                $sql .= " ORDER BY title ASC";
                break;
            case 'Z-A':  
                $sql .= " ORDER BY title DESC";
                break;
            case 'recent':
                $sql .= " ORDER BY created_at DESC"; 
                break;
            case 'oldest':
                $sql .= " ORDER BY created_at ASC";
                break;
            case 'popular':
                $sql .= " ORDER BY views DESC";
                break;
            default:
                $sql .= " ORDER BY created_at DESC";
                break;
        }
    
        try {
            $pin_obj = new PinManager;
            $pinned = $pin_obj->listPinned();
            $stmt = $this->dbHandler->run($sql, [$type, $genre]);
            if ($stmt->rowCount() > 0) {
                $posts =  $stmt->fetchAll();
                $posts = ($posts != false) ? $posts : [];
                $filter = ($this->filter_by() != false) ? $this->filter_by() : [];
                $genre = ($this->genres() != false) ? $this->genres() : [];
                $pinned = ($pinned != false) ? $pinned : [];
                
                 // Combine with genres and filter_by
                $result = [
                    'posts' => $posts,
                    'genres' => $this->genres(),
                    'type' => $this->type(),
                    'filter_by' => $this->filter_by(),
                    "pinned" => $pinned
                ];
                
                return $result;
            } else {
                return $result = [
                    'posts' => [],
                    'genres' => $this->genres(),
                    'type' => $this->type(),
                    'filter_by' => ($this->filter_by() != false) ? $this->filter_by() : [],
                    "pinned" => $pinned
                ];;
            }
        } catch (\Throwable $e) {
            return "Database Error: " . $e->getMessage();
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
    
    public function updatePostView($post_id, $user_id) {

    $sqlCheck = "SELECT COUNT(*) FROM post_views WHERE post_id = ? AND user_id = ?";
    
    try {
        $stmtCheck = $this->dbHandler->run($sqlCheck, [$post_id, $user_id]);
        $viewsCount = $stmtCheck->fetchColumn();

        // If the user has not viewed the post, update the view count
        if ($viewsCount == 0) {
            $sqlUpdate = "UPDATE posts SET views = views + 1 WHERE id = ?";
            $this->dbHandler->run($sqlUpdate, [$post_id]);
            
            $date = time();
            // Insert a record in the post_views table to mark that the user has viewed the post
            $sqlInsert = "INSERT INTO post_views (post_id, user_id, created_at) VALUES (?, ?, ?)";
            $this->dbHandler->run($sqlInsert, [$post_id, $user_id, $date]);

            return true;
        } else {
            // The user has already viewed the post
            return false;
        }
    } catch (\Throwable $e) {
        // Return the database error message
        return "Database Error: " . $e->getMessage();
    }
    }

    public function increasePostLike($post_id) {
         $sql = "UPDATE posts SET likes = likes + 1  WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$post_id]);
            if($stmt) {
              return 1;
            } else {
                return 0;
            }
        } catch (\Throwable $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }    
    }
    
    public function decreasePostLike($post_id) {
         $sql = "UPDATE posts SET likes = likes - 1  WHERE id = ?";
        try {
            $stmt = $this->dbHandler->run($sql, [$post_id]);
            if($stmt) {
              return 1;
            } else {
                return 0;
            }
        } catch (\Throwable $e) {
            // Return the database error message
            return "Database Error: " . $e->getMessage();
        }    
    }

    public function totalPosts() {
        $sql = "SELECT COUNT(*) AS total FROM posts";
        try {
            $stmt = $this->dbHandler->run($sql);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    
    public function totalViews() {
        $sql = "SELECT COUNT(*) AS total FROM post_views";
        try {
            $stmt = $this->dbHandler->run($sql);
            $result = $stmt->fetch();
            return ($result) ? $result['total'] : 0;
        } catch (\Throwable $e) {
            return "Database Error: ". $e->getMessage();
        }
    }
    
    public function getViewsTotalByRegistrationMonth() {
        // Get the current year
        $currentYear = date("Y");
    
        $months = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];
    
        $sql = "SELECT MONTH(FROM_UNIXTIME(created_at)) AS month_number, COUNT(*) AS views_count
                FROM post_views
                WHERE YEAR(FROM_UNIXTIME(created_at)) = ?
                GROUP BY month_number 
                ORDER BY month_number";
    
        try {
            $stmt = $this->dbHandler->run($sql, [$currentYear]); 
            $results = $stmt->fetchAll();
    
            $formattedResults = array_fill_keys($months, 0);
    
            foreach ($results as $row) {
                $monthName = date("M", mktime(0, 0, 0, $row['month_number'], 1, 2000));
                $formattedResults[$monthName] = (int)$row['views_count'];
            }
    
            return $formattedResults;
        } catch (\Throwable $e) {
            // Handle the database error
            return "Database Error: " . $e->getMessage();
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
