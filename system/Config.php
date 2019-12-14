<?php  
    ob_start();
    session_start();
    error_reporting(0);
    class Config{
        private static $_instance;
        private $host = "localhost";
        private $user = "root";
        private $pass = "";
        private $db = "paylis";
        private $conn;

        public static function getInstance() {
            if(!self::$_instance) { // If no instance then make one
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        function __construct($for = ""){
            ini_set("memory_limit", "1024M");
            $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db);
        }

        function connect_to_server(){
            if($this->conn){
                $result = "success";
            }
            else{
                $result = "failed";
            }
            
            return $result;
        }

        function connect(){
            return $this->conn;
        }
    }
?>