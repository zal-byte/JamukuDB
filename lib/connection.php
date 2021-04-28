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
            //$user = "zacybsec@localhost";
            $user = "database";
            $pass = "root";
            //$pass = "ZaCybSec45";
            $db_name = "jamuku";
            $this->connection = mysqli_connect($host, $user, $pass, $db_name) or die("Koneksi database gagal");
        }
        public function connects(){
            return $this->connection;
        }
    }

?>