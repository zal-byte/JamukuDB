<?php

    
    class Connection{
        public $connection;
        private static $instance = null;
        public static function getInstance(){
            if( self::$instance == null){
                self::$instance = new Connection();
            }
            return self::$instance;
        }
        public function __construct(){
            $host = "localhost";
            $user = "database";
            $pass = "root";
            $db_name = "jamuku";
            $this->connection = mysqli_connect($host, $user, $pass, $db_name) or die("Koneksi database gagal");
        }
        public function connects(){
            return $this->connection;
        }
    }

?>