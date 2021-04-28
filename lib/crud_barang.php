<?php

    class CRUD_B{
        public $connection;
        public function __construct($connection){
            $this->connection = $connection;
            date_default_timezone_set("Asia/Jakarta");

        }
        //Read
        function fetchProduct($page, $limit){ 
            $result["product"] = array();
            $query = "select * from produk  left join tipe on tipe.ProdType = produk.ProdType order by produk.ProdID desc limit ".$page.",".$limit;
            $sql = mysqli_query($this->connection, $query);
            if( $sql ){
                $re["status"] = true;
                $re["message"] = "Produk ada.";
                while( $row = mysqli_fetch_assoc( $sql ) ){
                    $re["ProdID"] = $row["ProdID"];
                    $re["ProdName"] = $row["ProdName"];
                    $re["ProdDesc"] = $row["ProdDesc"];
                    $re["ProdType"] = $row["ProdType"];
                    $re["ProdWeight"] = $row["ProdWeight"];
                    $re["ProdPrice"] = $row["ProdPrice"];
                    $re["ProdQuantity"] = $row["ProdQuantity"];
                    $re["ProdDate"] = $row["ProdDate"];
                    $re["ProdLove"] = $row["ProdLove"];
                    $re["ProdComm"] = $row["ProdComm"];
                    $re["ProdPict"] = $row["ProdPict"];

                    //Tipe
                    $re["TID"] = $row["TID"];
                    $re["ProdType"] = $row["ProdType"];
                    $re["ProdValue"] = $row["ProdValue"];
                    
                    array_push($result["product"], $re);
                }
                $this->print(json_encode($result));
            }else{
                //Database error
                $re["status"] = false;
                $re["message"] = "Database error.";
                array_push($result["product"], $re);
                $this->print(json_encode($result));
            }
        }

        function fetchProductDataView($ProdID){
            $result['productView'] = array();
            $query = "select * from produk left join tipe on tipe.ProdType = produk.ProdType where produk.ProdID = '".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "produk tersedia.";
                while($row = mysqli_fetch_assoc($sql)){
                    $re["ProdID"]= $row["ProdID"];
                    $re["ProdName"] = $row["ProdName"];
                    $re["ProdDesc"]= $row["ProdDesc"];
                    $re["ProdType"] = $row["ProdType"];
                    $re["ProdPrice"] = $row["ProdPrice"];
                    $re["ProdDate"] = $row["ProdDate"];
                    $re["ProdImage"] = $row["ProdPict"];
                    $re["ProdLove"] = $row["ProdLove"];
                    $re["ProdComm"] = $row["ProdComm"];
                    $re["ProdQuantity"]= $row["ProdQuantity"];
                    $re["ProdWeight"] = $row["ProdWeight"];
                    $re["ProdDate"] = $row["ProdDate"];
                    //table tipe
                    $re["ProdValue"] = $row["ProdValue"];
                    array_push($result["productView"], $re);
                }
            }else{
                $re["status"]= false;
                $re["message"] = "Barang tidak tersedia.";
                array_push($result['productView'], $re);
            }
            $this->print(json_encode($result));
        }

        function fetchPopularProduct($page, $limit){
            $result['popular'] = array();
            $query = "select * from produk where ProdLove > 5 order by ProdID desc limit ".$page.",".$limit;
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re['status'] = true;
                $re['message'] = 'Produk Tersedia.';
                while( $row = mysqli_fetch_assoc($sql)){
                    if($row['ProdLove']){
                        $re['ProdID'] = $row['ProdID'];
                        $re["ProdName"] = $row["ProdName"];
                        $re["ProdDesc"] = $row["ProdDesc"];
                        $re["ProdType"] = $row["ProdType"];
                        $re["ProdWeight"] = $row["ProdWeight"];
                        $re["ProdPrice"] = $row["ProdPrice"];
                        $re["ProdQuantity"] = $row["ProdQuantity"];
                        $re["ProdDate"] = $row["ProdDate"];
                        $re["ProdLove"] = $row["ProdLove"];
                        $re["ProdComm"] = $row["ProdComm"];
                        $re["ProdPict"] = $row["ProdPict"];
                        array_push($result['popular'], $re);
                    }
                }
            }else{
                $re['status'] = false;
                $re['message']= 'Kendala dalam database.';
                array_push($result['popular'], $re);
            }
            $this->print(json_encode($result));
        }

        function fetchMyCart($PUsername, $page, $limit){
            $result["cart"] = array();
            $query = "select * from pengguna inner join keranjang using (PUsername) inner join produk using (ProdID) where pengguna.PUsername='".$PUsername."' order by produk.ProdID desc limit ".$page.",".$limit;
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Tersedia.";
                while( $row = mysqli_fetch_assoc($sql) ){
                    $re["KerID"] = $row["KerID"];
                    $re["ProdID"] = $row["ProdID"];
                    $re["PUsername"] = $row["PUsername"];
                    $re["ProdName"] = $row["ProdName"];
                    $re["ProdImage"] = $row["ProdPict"];
                    $re["ProdPrice"] = $row["ProdPrice"];
                    array_push($result["cart"], $re);
                }
            }else{
                //Database error
                $re["status"] = false;
                $re["message"] = "Kendala dalam database.";
                array_push($result["cart"], $re);
            }
            $this->print(json_encode($result));
        }
        function buyProductHistory($PUsername, $page, $limit){
            $result['buyProductHistory'] = array();

            $query = "select * from beli where PUsername='".$PUsername."' order by BelID desc limit ".$page.",".$limit;
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                while( $row = mysqli_fetch_assoc($sql)){
                    $re['BelID'] = $row['BelID'];
                    $re['ProdID'] = $row['ProdID'];
                    $re['ProdImage'] = $row['ProdImage'];
                    $re['Quantity'] = $row['Quantity'];
                    $re['TotalPrice'] = $row['TotalPrice'];
                    $re['Address'] = $row['Address'];
                    $re['PUsername'] = $row['PUsername'];
                    $re['Status'] = $row['Status'];
                    array_push($result['buyProductHistory'], $re);
                }
            }else{
                $re["status"] = false;
                $re["message"] = "Tidak dapat mengambil data.";
                array_push($result['buyProductHistory'], $re);
            }
            $this->print(json_encode($result));
        }


        //Create
        function newProduct($param){
            $result["newProduct"] = array();
            
            $ProdPict_path = __DIR__."/img/produk/".$param['ProdType']."_".$param['ProdName'].".jpg";
            $ProdPict = $param['ProdType']."_".$param['ProdName'].".jpg";
            if( file_put_contents($ProdPict_path, base64_decode($param['ImageBase64']))){
                $query = "insert into produk (`ProdName`,`ProdDesc`,`ProdType`,`ProdWeight`,`ProdPrice`,`ProdQuantity`,`ProdDate`,`ProdLove`,`ProdPict`) values ";
                $query .= "('".$param['ProdName']."','".$param['ProdDesc']."','".$param['ProdType']."','".$param['ProdWeight']."','".$param['ProdPrice']."','".$param['ProdQuantity']."','".$param['ProdDate']."','0','0','".$ProdPict."')";
                $sql = mysqli_query($this->connection, $query);
                if($sql){
                    //Berhasil mengirim data
                    $re["status"] = true;
                    $re["message"] = "Data berhasil diunggah.";
                    array_push($result["newProduct"], $re);
                }else{
                    //Gagal mengirim data
                    $re["status"] = false;
                    $re["message"] = "Data gagal diunggah.";
                    array_push($result["newProduct"], $re);
                }
            }else{
                $re["status"] = false;
                $re["message"] = "Gambar tidak terunggah.";
                array_push($result["newProduct"], $re);
            }
            $this->print(json_encode($result));
        }
        function addToMyCart($param){
            $result['addToMyCart'] = array();
            $query = "insert into keranjang (`ProdID`,`PUsername`) values ('".$param['ProdID']."','".$param['PUsername']."')";
            if($this->checkMyCart($param['ProdID'], $param['PUsername']) == false){
                $sql = mysqli_query($this->connection, $query);
                if($sql){
                    $re["status"] = true;
                    $re["message"] = "Berhasil ditambahkan kedalam keranjang.";
                    array_push($result['addToMyCart'], $re);
                }else{
                    //Database error
                    $re["status"] = false;
                    $re["message"] = "Tidak dapat menambahkan.";
                    array_push($result['addToMyCart'], $re);
                }
            }else{
                array_push($result['addToMyCart'], $this->delMyCart($param['ProdID'], $param['PUsername']));
            }   
            $this->print(json_encode($result));
        }
        function delMyCart($ProdID, $PUsername){
            $query = "delete from keranjang where ProdID='".$ProdID."' and PUsername='".$PUsername."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Berhasil dihapus dari keranjang.";
            }else{
                $re["status"] = false;
                $re["message"] = "Gagal dihapus dari keranjang.";
            }
            return $re;
        }
        function checkMyCart($ProdID, $PUsername){
            $status = false;//bisa ditambah
            $query_check = "select * from keranjang where ProdID='".$ProdID."' and PUsername='".$PUsername."'";
            $sql_check = mysqli_query($this->connection, $query_check);
            if($sql_check){
                $row = mysqli_fetch_assoc($sql_check);
                if($row['ProdID']){
                    //Ada, tidak bisa ditambah ke keranjang lagi
                    $status = true;
                }else if(!$row['ProdID']){
                    //Tidak ada, bisa ditambah ke keranjang
                    $status = false;
                }
            }
            return $status;
        }
        function checkingMyCart($ProdID, $PUsername){
            $result['checkingMyCart'] = array();
            $query = "select * from keranjang where ProdID='".$ProdID."' and PUsername='".$PUsername."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if($row['ProdID']){
                    //Ada
                    $re['status']= true;
                    $re['message'] = 'produk_sudah_dikeranjang';
                    array_push($result['checkingMyCart'], $re);
                }else if(!$row['ProdID']){
                    //Tidak ada
                    $re['status'] = false;
                    $re['message'] = 'produk_belum_dikeranjang';
                    array_push($result['checkingMyCart'], $re);
                }
            }
            $this->print(json_encode($result));
        }
        function buyProduct($param){
            $result['buyProduct'] = array();
            //pertama kita cek jumlah stok produk terlebih dahulu
            $ProdQuantity = (int) $this->checkProductQuantity($param["ProdID"]);
            if( $ProdQuantity <= 0){
                $re["status"] = false;
                $re["message"] = "Produk sudah habis, tidak dapat dipesan lagi..";
                array_push($result['buyProduct'], $re);
                $this->print(json_encode($result));
                //Produk sudah habis, tidak bisa dibeli.
            }else{
               if( ($ProdQuantity - $param['Quantity']) < 0){
                $re["status"] = false;
                $re["message"] = "Produk sudah habis, tidak dapat dipesan lagi.";
                array_push($result['buyProduct'], $re);
                $this->print(json_encode($result));
               }else{
                $finalQuantity = ($ProdQuantity - $param['Quantity']);
                //Disini kita akan mencek dulu apakah ada pembayaran yang sama
                if($this->checkIsAvailable($param['ProdID'], $param['PUsername']) == true){
                    //Tidak ada data yang sama disini, melanjutkan update dan insert kedalam tabel beli
                    if($this->updateProdQuantity($finalQuantity, $param['ProdID']) == true){
                        //berhasil diupdate
                        $this->addToBuyTables($param);
                    }else{
                        //gagal update
                        $re["status"] = false;
                        $re["message"] = "Tabel produk gagal diperbarui.";
                        array_push($result['buyProduct'], $re);
                        $this->print(json_encode($result));
                    }
                }else{
                    //SamePayment
                    $re["status"] = false;
                    $re["message"]= "SamePayment";
                    $re["ProdID"] = $param['ProdID'];
                    array_push($result['buyProduct'], $re);
                    $this->print(json_encode($result));
                }
               }
            }
        }
        function checkIsAvailable($ProdID, $PUsername){
            $status = false;
            $query = "select * from beli where ProdID='".$ProdID."' and PUsername='".$PUsername."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $row = mysqli_fetch_assoc($sql);
                if(!$row['ProdID']){
                    //Tidak ada proses pembelian yang sama, oleh karena itu kita akan melakukan proses pembelian.
                    $status = true;
                }else{
                    //Ada proses pembelian yang sama, oleh karena itu kita kaan melanjutkan proses yang tadi.
                    $status = false;
                }
            }else{
                //Masalah pada database
                //Gabisa balikin nilai true atau false
            }
            return $status;
        }
        function getTotalPrice($ProdID, $Quantity){
            $query = "select * from produk where ProdID='".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            $row = mysqli_fetch_assoc($sql);
            $normalPrice = $row['ProdPrice'];
            return ( $normalPrice * $Quantity );
        }
        function addToBuyTables($param){
            $result['buyProduct'] = array();
            $query = "insert into beli (`ProdID`,`ProdImage`,`Quantity`,`TotalPrice`,`Address`,`PUsername`,`BelDate`,`Status`) values ('".$param['ProdID']."','".$param['ProdImage']."','".$param['Quantity']."','".$this->getTotalPrice($param['ProdID'], $param['Quantity'])."','".$param['Address']."','".$param['PUsername']."','".date("Y-m-d")."','proses')";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Berhasil ditambahkan kedalam tabel pembelian.";
                array_push($result['buyProduct'], $re);
            }else{
                $re["status"] = false;
                $re["message"] = "Gagal menambahkan kedalam tabel pembelian.";
                array_push($result['buyProduct'], $re);
            }
            $this->print(json_encode($result));
        }
        function updateProdQuantity($finalQuantity, $ProdID){
            $status = false;
            $query = "update produk set ProdQuantity = '".$finalQuantity."' where ProdID='".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){   
                $status = true;
            }else{
                $status = false;
            }
            return $status;
        }
        function checkProductQuantity($ProdID){
            $query = "select ProdQuantity from produk where ProdID='".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            $row = mysqli_fetch_assoc($sql);
            return $row["ProdQuantity"];
        }
        function deleteProduct($ProdID){
            $result['deleteProduct'] = array();

            $query = "delete from produk where ProdID='".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Berhasil dihapus.";
                array_push($result['deleteProduct'], $re);
            }else{
                //Gagal menghapus produk
                $re["status"] = false;
                $re["message"] = "Tidak dapat dihapus.";
                array_push($result['deleteProduct'], $re);
            }
            $this->print(json_encode($result));
        }

        //CRON JOB METHOD
        //Hapus payment yang belum dibayar lebih dari satu hari
        //menggunakan cron-job.org
        function deletePayment(){
            //Pertama ambil tanggal yang digunakan oleh cronjob
            $cron_day = date("d");
            //lalu ambil tanggal dari database
            $query = "select BelDate from beli order by BelDate desc";
            $sql = mysqli_query($this->connection,$query);
            while($row = mysqli_fetch_assoc($sql)){
                $explode = explode("-", $row["BelDate"]);
                if(($explode[2] % $cron_day ) == 1){
                    $this->deletePaymentRequest($row["BelDate"]);
                }
            }
        }
        function deletePaymentRequest($date){
            $result['deletePaymentRequest'] = array();
            $query = "delete from beli where BelDate='".$date."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Payment Request Has Been Deleted.";
                array_push($result['deletePaymentRequest'], $re);
            }else{ 
                $re["status"] = false;
                $re["message"] = "Payment Request Can't to delete.";
                array_push($result['deletePaymentRequest'], $re);
            }
            $this->print(json_encode($result));
        }


        //Update quantity of product
        //Menyetok barang / isi stok barang
        function addQuantity($ProdID, $Quantity){
            $result['addQuantity'] = array();
            $query = "update produk set ProdQuantity='".$Quantity."' where ProdID='".$ProdID."'";
            $sql = mysqli_query($this->connection, $query);
            if($sql){
                $re["status"] = true;
                $re["message"] = "Stok barang berhasil ditambahkan";
                array_push($result['addQuantity'], $re);
            }else{
                $re["status"] = false;
                $re["message"] = "Gagal menambahkan stok barang.";
                array_push($result['addQuantity'], $re);
            }
            $this->print(json_encode($result));
        }

        //Hello there !
        function print($string){
            echo $string;
        }
    }

?>