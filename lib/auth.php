    <?php

    class Auth{
        public $username;
        public $password;
        public $connection;
        public function __construct($connection){
            $this->connection = $connection;
        }
        function login($parameter){
            $result["login"] = array();
            $query = "select * from pengguna where PUsername ='".$parameter["PUsername"]."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if(!$row["PUsername"]){
                    ///Tidak ada pengguna yang ditemukan
                    $re["status"] = false;
                    $re["message"] = "Pengguna tidak ditemukan.";
                    array_push($result["login"], $re);
                }else{
                    //Pengguna ditemukan
                    if($row["PPassword"] == $parameter["PPassword"]){
                        //Login Berhasil
                        $re["status"] = true;
                        $re["message"] = "Login berhasil.";
                        $re["PID"] = $row["PID"];
                        $re["PUsername"] = $row["PUsername"];
                        $re["PName"] = $row["PName"];
                        $re["PEmail"] = $row["PEmail"];
                        $re["PPhone"] = $row["PPhone"];
                        $re["PAddress"] = $row["PAddress"];
                        $re["PProfilePicture"] = $row["PProfilePicture"];
                        $re['PRole'] = $row['PRole'];
                        array_push($result["login"], $re);
                    }else{
                        //Kata sandi salah
                        $re["status"] = false;
                        $re["message"] = "Kata sandi salah.";
                        array_push($result["login"], $re);
                    }
                }
            }else{
                $re["status"] = false;
                $re["message"] = "Database error.";
                array_push($result["login"], $re);
            }
            $this->print(json_encode($result));
        }

        function signup($parameter){
            $result["signup"] = array();
            //Pertama kita akan mengecek didalam database apakah ada user yang sama, jika ada maka return false ( error );
            $query = "insert into pengguna (`PUsername`,`PPassword`,`PName`,`PEmail`,`PPhone`,`PAddress`,`PProfilePicture`,`PRole`) ";
            $query .= "values ('".$parameter["PUsername"]."','".$parameter["PPassword"]."','".$parameter["PName"]."','".$parameter["PEmail"]."','".$parameter["PPhone"]."','".$parameter["PAddress"]."','".$parameter["PProfilePicture"]."','pengguna')";
            if($this->checkUser($parameter["PUsername"]) == false){
                $sql = mysqli_query($this->connection, $query);
                if($sql){
                    //Berhasil memasukan data kedalam database
                    $re["status"] = true;
                    $re["message"] = "Daftar berhasil.";
                    array_push($result["signup"], $re);
                }else{
                    //Gagal memasukan data kedalam database
                    $re["status"] = false;
                    $re["message"] = "Daftar gagal.";
                    array_push($result["signup"], $re);
                }
                $this->print(json_encode($result));
            }else{
                //Ada pengguna yang sama return error
                $re["status"] = false;
                $re["message"] = "Daftar gagal, nama pengguna telah digunakan orang lain.";
                array_push($result["signup"], $re);
                $this->print(json_encode($result));
            }
        }
        function checkUser($PUsername){
            $status = false;
            $querys = "select * from pengguna where PUsername='".$PUsername."'";
            $sqls = mysqli_query($this->connection, $querys);
            if($sqls){
                $row = mysqli_fetch_assoc($sqls);
                if($row["PUsername"] != null || $row["PUsername"]){
                    //Ada pengguna yang sama return false
                    $status = true;
                }else{
                    //Tidak ada pengguna yang sama, melanjutkan registrasi
                    $status = false;
                }
            }
            return $status;
        }

        function print($string){
            echo $string;
        }
    }

?>