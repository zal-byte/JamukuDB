<?php

	define("config","conf.conf");
	define("_HOST","localhost");
	class SqlHandler{
		public  $con;
		const CONNECT_CON = "CONNECT";
		const CONNECT_CREATE = "CREATE";
		const CONNECT_PREPARE = "PREPARE";
		const CONNECT_CONF = "CONFIG";
		private static $instance= null;
		public static function getInstance(){
			if(self::$instance == null){
				self::$instance= new SqlHandler();
			}
			return self::$instance;
		}	
		public function con($do = "CONNECT", $host = "localhost", $user = "root", $pass = "", $db = null){
			if($do == self::CONNECT_CON){
				$host = $host !== "localhost" ? $host : _HOST;
				$user = $user != "root" ? $user : $user;
				$pass = $pass != "" ? $pass : $pass;
				$db = $db != null ? $db : die("Please set your db_name");
				$this->con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_connect_error());
			}else if($do == self::CONNECT_CREATE){
				if($host == "localhost" || !$host){
					$host = "localhost";
				}else{
					$host = $host;
				}
				$user = $user != "root" ? $user : $user;
				$pass = $pass != "" ? $pass : $pass;
				$db = $db != null ? $db : die("Please set your db_name");
				$this->createConfig($host, $user, $pass, $db);
			}else if($do == self::CONNECT_PREPARE){
				if ( $this->check(config) != true ){
					if($host == "localhost" || !$host){
						$host = "localhost";
					}else{
						$host = $host;
					}
					$user = $user != "root" ? $user : $user;
					$pass = $pass != "" ? $pass : $pass;
					$db = $db != null ? $db : die("Please set your db_name");
					$this->createConfig($host, $user, $pass, $db);
				}else{
					$host = $host !== "localhost" ? $host : _HOST;
					$user = $user != "root" ? $user : $user;
					$pass = $pass != "" ? $pass : $pass;
					$db = $db != null ? $db : die("Please set your db_name");
					$this->con = mysqli_connect($host, $user, $pass, $db) or die(mysqli_connect_error());
				}
			}else if($do == self::CONNECT_CONF){
				if($this->check(config) != true){
					die("There's no conf.conf file");
				}else{
					$host = "";
					$user = "";
					$pass = "";
					$db_name = "";
					$ex = explode("?", file_get_contents("conf.conf"));
					for($i = 0; $i < count($ex); $i++){
						if($ex[$i] == "host"){
							$host .= $ex[$i+1] != null || $ex[$i+1] != "" ? $ex[$i+1] : "localhost";
						}else if($ex[$i] == "user"){
							$user .= $ex[$i+1] != null || $ex[$i+1] != "" ? $ex[$i+1] : "root";
						}else if($ex[$i] == "pass"){
							$pass .= $ex[$i+1] != null || $ex[$i+1] != "" ? $ex[$i+1] : "";
						}else if($ex[$i] == "db_name"){
							$db_name .= $ex[$i+1] != null || $ex[$i+1] != "" ? $ex[$i+1] : die("There's no database name");
						}
					}
					if($host != "" || $host != null){
						if($user != "" || $user != null){
							if($pass != null){
								if($db_name != null || $db_name != ""){
									$this->con(self::CONNECT_CON, $host, $user, $pass, $db_name);
								}else{
									die("Var db_name = null");
								}
							}else{
								die("Var pass = null");
							}
						}else{
							die("Var user = null");
						}
					}else{
						die("Var host = null");
					}
				}
			}
		}

		function createConfig($host, $user, $pass, $db){
			$data = "host?".$host."?user?".$user."?pass?".$pass."?db_name?".$db;
			if(file_put_contents(config, $data)){
				$this->con(self::CONNECT_CONF, $host, $user, $pass, $db);
			}
		}

		 function check($name){
			if(!file_exists($name)){
				return false;	
			}else{
				return true;
			}
		}


		function update($table, $param1, $param2){
			$temp_query = "update ".$table." set ";
			$temp_num = 1;
			$num = count($param1);
			foreach ($param1 as $key => $value) {
				# code...
				$temp_query .= "`".$key."`='".$value."'";
				if($temp_num < $num){
					$temp_query .= ",";
				}
				$temp_num++;
			}
			$temp_query .= " where ";
			$temp_num = 1;
			$nums = count($param2);
			foreach ($param2 as $key => $value) {
				# code...
				$temp_query .= str_replace((string) $temp_num-1, "", $key)."='".$value."'";
				if($temp_num < $nums){
					$temp_query .= " and ";
				}
				$temp_num++;
			}
			print_r($temp_query);
		}

		 function insert($table, $param){
		 	$temp_query = "insert into ".$table." (";
		 	$temp_num = 1;
		 	$num = count($param);
		 	foreach ($param as $key => $value) {
		 		# code...
		 		$temp_query .= "`".$key."`";
		 		if($temp_num < $num) {
		 			$temp_query .= ",";
		 		}
		 		$temp_num++;
		 	}
		 	$temp_query .= ") values (";
		 	$temp_num = 1;
		 	foreach ($param as $key => $value) {
		 		# code...
		 		$temp_query .= "'".$value."'";
		 		if($temp_num < $num){
		 			$temp_query .= ",";
		 		}
		 		$temp_num++;
		 	}
		 	$temp_query .= ")";
		 	return $this->query($temp_query);
		 }
		 function query($query = ""){
			$query != "" ? $query : die("Insert your query ");
			$sql = mysqli_query($this->con ,$query);

			if($sql){
				return $sql;
			}else{
				die("Exec query error.");
			}
		}
		 function num($sql = null){
			$sql = is_object($sql) == true ? $sql : die("SqlHandler->num(parameter) is not mysqli_result");
			$sql = $sql != null ? $sql : die("There's no mysqli_result");
			return mysqli_num_rows($sql);
		}
		 function assoc($sql = null){
			$sql != null ? $sql : die("Cannot fetch data because there's no sql response.");
			$sql = is_object($sql) == true ? $sql : die("SqlHandler->assoc(parameter) is not mysqli_result");
			return mysqli_fetch_assoc($sql);
		}
		function secure($value, $type){
			if($type == static::_NUMERIC){
				if(is_numeric($value)){
					return $value;
				}else{
					die($value." : is not numeric.");
				}
			}else if($type == static::_STRING){
				if(is_string((string) $value)){
					return $value;
				}else{
					die($value." : is not string.");
				}
			}
		}
	}
	
	class A extends SqlHandler{
		private static $a_instance=null;
		const _NUMERIC = "NUMERIC";
		const _STRING = "STRING";
		public static function  getInstance(){
			if(self::$a_instance == null){
				self::$a_instance = new A();
			}
			return self::$a_instance;
		}
		public function __construct(){

		}
		public static function select($parameter){
			return "SELECT ".$parameter." ";
		}
		public static function from($parameter){
			return "FROM ".$parameter." ";
		}
		public static function orderby($parameter){
			return "ORDER BY ".$parameter." ";
		}
		public static function asc_desc($parameter){
			return $parameter." ";
		}
		public static function where($name = "", $value = ""){
			return "WHERE ".$name." = '".$value."' ";
		}
		public static function like($name = "", $value = ""){
			return "WHERE ".$name." like '%".$value."%' ";
		}
		public static function and(){
			return "AND ";
		}
	}
?>