<?php

    class CRUD_PF{
        public $connection;
        private static $instance = null;
        public static function getInstance($connection){
          if(self::$instance == null){
            self::$instance = new CRUD_PF($connection);
          }
          return self::$instance;
        }
        public function __construct($connection){
            $this->connection = $connection;
        }
        //Read
        function fetchProfileData($PUsername){
            $result['fetchProfileData'] = array();

            $query = "select * from pengguna where PUsername='".$PUsername."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if($row["PUsername"]){
                    $re["status"] = true;
                    $re["message"] = "Pengguna tersedia.";
                    $re["PUsername"] = $row["PUsername"];
                    $re["PName"] = $row["PName"];
                    $re["PEmail"] = $row["PEmail"];
                    $re["PPhone"] = $row["PPhone"];
                    $re["PAddress"] = $row["PAddress"];
                    $re["PProfilePicture"] = $row["PProfilePicture"];
                    $re["PRole"] = $row["PRole"];
                    array_push($result['fetchProfileData'], $re);
                }else{
                    $re["status"] = false;
                    $re["message"] = "Pengguna tidak ditemukan.";
                    array_push($result['fetchProfileData'], $re);
                }
            }else{
                //Database error
                $re["status"] = false;
                $re["message"] = "Database Error.";
                array_push($result['fetchProfileData'], $re);
            }
            $this->print($result);
        }
        //Update
        function updateProfileData($param){
            // print_r($param);
            $uid = base64_encode(uniqid());
            
            if($param["Name"] == "ImageBase64"){
                $path = "lib/img/profilepengguna/";
                $filename = $param["PUsername"]."_".$uid.".jpg";
              
              
                $sql = mysqli_query($this->connection, "select * from pengguna where PUsername='".$param["PUsername"]."'");
                $row = mysqli_fetch_assoc($sql);
                
                    if($param["Name"] == "ImageBase64"){
                        if(file_exists($path.$row["PProfilePicture"])){
                          unlink($path.$row["PProfilePicture"]);
                        }
                    
                    $sqls = mysqli_query($this->connection, "update pengguna set PProfilePicture='".$filename."' where PUsername='".$param["PUsername"]."'");
                    if($sqls){
                        if(@file_put_contents($path.$filename, base64_decode($param["Value"]))){
                            $this->print(array("updateProfileData"=>array(array("status"=>true,"message"=>"Gambar berhasil di unggah"))));
                        }else{
                            $this->print(array("updateProfileData"=>array(array("status"=>false, "message"=>"Gambar gagal di unggah"))));
                        }
                    }else{
                        $this->print(array(array("updateProfileData"=>array("status"=>false,"message"=>"Gagal memperbarui data"))));
                    }
                }

            }else{
                $this->executeSql("update pengguna set ".$param["Name"]." = '".$param["Value"]."' where PUsername='".$param["PUsername"]."'");
            }
        }
        function executeSql($query){
        	$result['updateProfileData'] = array();
        	$sql = mysqli_query($this->connection, $query);
        	if($sql){
        		$re["status"] = true;
        		$re["message"] = "Berhasil diperbarui.";
        		array_push($result['updateProfileData'], $re);
        	}else{
        		$re["status"] = false;
        		$re["message"] = "Gagal diperbarui.";
        		array_push($result['updateProfileData'], $re);
        	}
          $this->print($result);
        }
      //   function updateProfileData($param){
      //       $result['updateProfileData'] = array();

      //       $query = "update pengguna set ";
      //       if(isset($param['PName'])){
      //           $query .= "`PName`='".$param['PName']."',";
      //       }
      //       if(isset($param['PEmail'])){
      //           $query .= ",`PEmail`='".$param['PEmail']."'";
      //       }
      //       if(isset($param['PPhone'])){
      //           $query .= ",`PPhone`='".$param['PPhone']."'";
      //       }
      //       if(isset($param['PAddress'])){
      //           $query .= ",`PAddress`='".$param['PAddress']."'";
      //       }
      //       if(isset($param['ImageBase64'])){
      //           $path_for_image = __DIR__."/img/profilepengguna/".$param['PUsername'].".jpg";
      //           $path_for_database = $param['PUsername'].".jpg";
      //           $query .= "`PProfilePicture`='".$path_for_database."'";
      //           if($this->uploadImage($path_for_image, $param['ImageBase64']) == true){
      //               $re["status"] = true;
      //               $re["message"] = "Gambar berhasil diunggah.";
      //               array_push($result['updateProfileData'], $re);
      //           }else{
      //               $re["status"] = false;
      //               $re["message"] = "Gagal mengunggah gambar.";
      //               array_push($result['updateProfileData'], $re);
      //           }
      //       }
	  			// $query .= " where PUsername='".$param["PUsername"]."'";
	     //        $sql = mysqli_query($this->connection, $query);
	     //        if($sql){   
	     //            $re["status"] = true;
	     //            $re["message"] = "Profile berhasil diperbarui.";
	     //            array_push($result['updateProfileData'], $re);
	     //        }else{
	     //            //Gagal mengubah data
	     //            $re["status"] = false;
	     //            $re["message"] = "Gagal memperbarui data pengguna.";
	     //            array_push($result['updateProfileData'], $re);
	     //        }
          
      //       $this->print(json_encode($result));
      //   }
        function uploadImage($path, $data){
            $status = false;
            if( file_put_contents($path, base64_decode($data)) ){
                $status = true;
            }else{
                $status = false;
            }
            return $status;
        }
        function changePassword($param){
            $result['changePassword'] = array();
            $query = "update pengguna set ";
            if($param["PPassword"] === $param["PPassword_verify"] || $param["PPassword"] == $param["PPassword_verify"]){
                $query .= "`PPassword`='".$param["PPassword"]."' where PUsername='".$param["PUsername"]."'";
                $sql = mysqli_query($this->connection, $query);
                if($sql){
                    $re["status"] = true;
                    $re["message"] = "Kata sandi berhasil diperbarui.";
                    array_push($result['changePassword'], $re);
                }else{
                    //Gagal memperbarui kata sandi
                    $re["status"] = false;
                    $re["message"] = "Gagal memperbarui kata sandi.";
                    array_push($result['changePassword'], $re);
                }
            }else{
                $re["status"] = false;
                $re["message"] = "Kata sandi yang dimasukan tidak sama.";
                array_push($result['changePassword'], $re);
            }
            $this->print($result);
        }
        function print($string){
        	echo json_encode($string);
        }
    }

?>