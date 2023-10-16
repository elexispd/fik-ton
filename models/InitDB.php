<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../vendor/autoload.php");
  

class InitDB{
    public $dbConnect;
    private $db = "fikkton";
    private $username = "root";
    private $password = "";
    private $host = "localhost";
    public function __construct()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=utf8mb4";

        try {
            $this->dbConnect = new \PDO($dsn, $this->username, $this->password);
            return $this->dbConnect;
        } catch (\PDOException $e) {
            //SEND THIS TO AUDIT SERVICE FOR LOGGING
        }
        
    }


    public function run($sql, $args = NULL)
    {
        $stmt=null;
        
            if (!$args)
        {
            return $this->dbConnect->query($sql);
        }
        try {
            $stmt = $this->dbConnect->prepare($sql);
            $stmt->execute($args);
        } catch (PDOException $e) {
            //SEND THIS TO AUDIT SERVICE FOR LOGGING
            echo $e->getMessage();
        }
        
        return $stmt;
    }
}




/* 

USAGE

$unitTest=new InitDB(DB_OPTIONS[2], DB_OPTIONS[0],DB_OPTIONS[1],DB_OPTIONS[3]);
$stmt=$unitTest->run("SELECT * FROM commission_tb WHERE id <= ?", [5]);
echo $stmt->rowCount(); */
?>