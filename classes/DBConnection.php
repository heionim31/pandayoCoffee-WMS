<?php
    if (!defined('DB_SERVER')) {
        require_once("../initialize.php");
    }

    class DBConnection{
        private $connectionString = "postgresql://alucemajqcydoqcu:v2_42XaU_7Gg8RKamwJfzNvmjRGYwmmK@db.bit.io:5432/SBIT3HSIA/SIA";
        
        public $conn;
        
        public function __construct(){
            if (!isset($this->conn)) {
                $this->conn = pg_connect($this->connectionString);
                if (!$this->conn) {
                    echo 'Cannot connect to database server';
                    exit;
                }            
            }    
        }
        
        public function __destruct(){
            if (is_resource($this->conn)) {
                pg_close($this->conn);
            }
        }
    }
?>

