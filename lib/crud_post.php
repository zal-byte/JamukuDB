<?php

    class CRUD_P{
        public $connection;
        public function __construct($connection){
            $this->connection = $connection;
        }
        //create
        function sendComment($param){
            $result['sendComment'] = array();

            $query = "insert into komentar (`ProdID`,`PUsername`,`KMessage`,`KDate`) values ";
            $query .= "('".$param['ProdID']."','".$param['PUsername']."','".$param['KMessage']."','".$param['KDate']."')";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                //upquery
                $up = "select ProdComm from produk where ProdID='".$param['ProdID']."'";
                $up_s= mysqli_query($this->connection, $up);
                $ro = mysqli_fetch_assoc($up_s);

                $k = "update produk set ProdComm='".($ro['ProdComm'] + 1)."' where ProdID='".$param['ProdID']."'";
                $s = mysqli_query($this->connection, $k);
                if($s){
                    $re["status"] = true;
                    $re["message"] = "Komentar berhasil ditambahkan.";
                }else{
                    $re["status"] = false;
                    $re["message"] = "Komentar berhasil ditambahkan. Namun tidak dapat diperbarui.";
                }
                array_push($result['sendComment'], $re);
            }else{
                //Tidak dapat mengirim komentar
                $re["status"] = false;
                $re["message"] = "Komentar tidak dapat ditambahkan.";
                array_push($result['sendComment'], $re);
            }
            $this->print(json_encode($result));
        }

        //check ProdLove and doLike or doUnlike
        function checkingLike($ProdID, $PUsername){
            $result['checkingLike'] = array();
            $query = "select * from suka where ProdID ='".$ProdID."' and PUsername='".$PUsername."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if($row['ProdID']){
                    $re['status'] = true;
                    array_push($result['checkingLike'], $re);
                }else if(!$row['ProdID']){
                    $re['status'] = false;
                    array_push($result['checkingLike'], $re);
                }
                $this->print(json_encode($result));
            }
        }
        function checkLike($param){
            $query = "select * from suka where ProdID ='".$param['ProdID']."' and PUsername='".$param['PUsername']."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if($row['ProdID'] || $row['PUsername'] == $param['PUsername']){
                    //Ada pengguna, melakukan doUnlike
                    $this->doUnlike($param);
                }else if(!$row['ProdID']){
                    //Tidak ada pengguna, melakukan doLike
                    $this->doLike($param);
                }
            }
        }
        function doLike($param){
            $result['doLike'] = array();

            //Kita cek ProdLove lalu update ProdLovenya
            $query_check = "select * from produk where ProdID='".$param['ProdID']."'";
            $sql_check = mysqli_query($this->connection, $query_check);
            $prod_love = 0;
            if($sql_check){
                $row_check = mysqli_fetch_assoc($sql_check);
                $prod_love = $prod_love + (int) $row_check['ProdLove'];

                //Updating
                $query_update = "update produk set ProdLove='".($prod_love + 1)."' where ProdID='".$param['ProdID']."'";
                $sql_update = mysqli_query($this->connection, $query_update);
                if($sql_update){
                    //Update berhasil, tinggal insert data kedalam table suka
                    $query = "insert into suka (`ProdID`,`PUsername`) values ('".$param['ProdID']."','".$param['PUsername']."')";
                    $sql = mysqli_query($this->connection, $query);
                    if($sql){
                        $re['status'] = true;
                        $re['message'] = 'Suka berhasil ditambahakn.';
                        array_push($result["doLike"], $re);
                    }else{
                        //Gagal menambahkan data kedalam table suka
                        $re["status"] = false;
                        $re["message"] = "Tidak dapat menambahkan data kedalam suka.";
                        array_push($result['doLike'], $re);
                    }
                }else{
                    //Update gagal, error :(
                    $re["status"] = false;
                    $re["message"] = "Update gagal.";
                    array_push($result['doLike'], $re);
                }
            }
            $this->print(json_encode($result));
        }
        function doUnlike($param){
            $result['doLike'] = array();
            //Cek ProdLove
            $query_check = "select * from produk where ProdID='".$param['ProdID']."' and PUsername='".$param["PUsername"]."'";
            $sql_check = mysqli_query($this->connection, $query_check);
            $prod_love = 0;
            if($sql_check){
                $row_check = mysqli_fetch_assoc($sql_check);
                $prod_love = $prod_love + (int) $row_check['ProdLove'];
                //Update dulu
                $query_up = "update produk set ProdLove='".($prod_love - 1)."' where ProdID='".$param['ProdID']."'";
                $sql_up = mysqli_query($this->connection, $query_up);
                if($sql_up){
                    //Hapus data suka
                    $query = "delete from suka where ProdID='".$param['ProdID']."' and PUsername='".$param['PUsername']."'";
                    $sql = mysqli_query($this->connection, $query);
                    if($sql){
                        $re['status'] = true;
                        $re['message'] = 'Unlike berhasil.';
                        array_push($result['doLike'], $re);
                    }else{
                        //Gagal dihapus
                        $re['status'] = false;
                        $re['message'] = 'Unlike gagal.';
                        array_push($result['doLike'], $re);
                    }
                }else{
                    //Gagal update
                    $re["status"] = false;
                    $re["message"] = "Gagal mengupdate.";
                    array_push($result['doLike'], $re);
                }
            }else{
                //Database error
                $re["status"] = false;
                $re["message"] = "Database error.";
                array_push($result['doLike'], $re);
            }
            $this->print(json_encode($result));
        }

        //read
        function getComment($ProdID, $page, $limit){
            $result['getComment'] = array();

            $query = "select * from komentar left join pengguna on komentar.PUsername = pengguna.PUsername where komentar.ProdID='".$ProdID."' order by komentar.KID desc limit ".$page.",".$limit;
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Komentar tersedia.";
                while( $row = mysqli_fetch_assoc($sql) ){
                    $re['ProdID'] = $row['ProdID'];
                    $re['PUsername']= $row['PUsername'];
                    $re['KMessage'] = $row['KMessage'];
                    $re["PProfilePicture"] = $row["PProfilePicture"];
                    $re["PName"] = $row["PName"];
                    $re["KDate"] = $row["KDate"];
                    $re["KID"] = $row["KID"];
                    array_push($result['getComment'], $re);
                }
            }else{
                //Database Error
                $re["status"] = false;
                $re["message"] = "Database error.";
                array_push($result['getComment'], $re);
            }
            $this->print(json_encode($result));
        }

        //delete
        function deleteComment($KID){
            $result['deleteComment'] = array();

            $query = "delete from komentar where KID='".$KID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Komentar berhasil dihapus.";
                array_push($result['deleteComment'], $re);
            }else{
                //Gagal menghapus komentar
                $re["status"] = false;  
                $re["message"] = "Komentar tidak dapat dihapus.";
                array_push($result['deleteComment'], $re);
            }
            $this->print(json_encode($result));
        }

        //update
        function updateComment($KID, $KMessage){
            $result['updateComment'] = array();

            $query = "update komentar set KMessage='".$KMessage."' where KID='".$KID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Komentar berhasil diubah.";
                array_push($result['updateComment'], $re);
            }else{
                $re["status"] = false;
                $re["message"] = "Komentar tidak dapat diubah.";
                array_push($result['updateComment'], $re);
            }
            $this->print(json_encode($result));
        }

        function print($string){
            echo $string;
        }

    }

?>