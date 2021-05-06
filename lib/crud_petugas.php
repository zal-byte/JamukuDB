<?php
	class Petugas{
		private static $instance = null;
		public static function getInstance($sql){
			if(self::$instance == null){
				self::$instance = new Petugas($sql);
			}
			return self::$instance;
		}
		public $sql;
		public function __construct($sql){
			$this->sql= $sql;
			$this->sql->con($this->sql::CONNECT_PREPARE,"localhost","database","root","jamuku");
		}
		public function addQuantity($array){
			if($this->isProductAvailable($array["ProdID"]) == true ){
				$result = $this->sql->query("update produk set ProdQuantity='".$array["NewQuantity"]."' where ProdID='".$array["ProdID"]."'");
				if($result){
					$this->print(array("addQuantity"=>array("status"=>true,"msg"=>"Jumlah produk berhasil ditambah.")));
				}else{
					$this->print(array("addQuantity"=>array("status"=>false,"msg"=>"Jumlah produk gagal ditambah.")));
				}
			}else{
				$this->print(array("addQuantity"=>array("status"=>false, "msg"=>"Produk tidak ada")));
			}
		}
		public function changeProdPrice($array){
			if($this->isProductAvailable($array["ProdID"]) == true ){
				$result = $this->sql->query("update produk set ProdPrice='".$array["NewPrice"]."' where ProdID='".$array["ProdID"]."'");
				if($result){
					$this->print(array("newPrice"=>array("status"=>true,"msg"=>"Harga produk berhasil di ubah.")));
				}else{
					$this->print(array("newPrice"=>array("status"=>true, "msg"=>"Tidak dapat mengubah harga produk, silahkan coba lagi.")));
				}
			}else{
				$this->print(array("newPrice"=>array("status"=>false,"msg"=>"Produk tidak ada.")));
			}
		}
		public function changeProdDescription($array){
			if($this->isProductAvailable($array["ProdID"]) == true){
				$result = $this->sql->query("update produk set ProdDesc='".$array["NewDescription"]."' where ProdID='".$array["ProdID"]."'");
				if($result){
					$this->print(array("newDescription"=>array("status"=>true,"msg"=>"Deskripsi produk berhasil di ubah.")));
				}else{
					$this->print(array("newDescription"=>array("status"=>false,"msg"=>"Deskripsi produk gagal di ubah.")));
				}
			}else{
				$this->print(array("newDescription"=>array("status"=>false, "msg"=>"Produk tidak ada.")));
			}
		}
		public function changeProductName($array){
			if($this->isProductAvailable($array["ProdID"]) == true){
				$result = $this->sql->query("update produk set ProdName='".$array["NewName"]."' where ProdID='".$array["ProdID"]."'");
				if($result){
					$this->print(array("newName"=>array("status"=>true,"msg"=>"Nama produk berhasil diperbarui.")));
				}else{
					$this->print(array("newName"=>array("status"=>false,"msg"=>"Nama produk gagal diperbarui.")));
				}
			}else{
				$this->print(array("newName"=>array("status"=>false,"msg"=>"Produk tidak ada.")));
			}
		}
		public function isProductAvailable($ProdID){
			return $this->sql->num($this->sql->query("select * from produk where ProdID='".$ProdID."'")) > 0 ? true : false;
		}

		public function fetchAllPayment(){
			$rod["fetchAllPayment"] = array();
			$result = $this->sql->query("select * from beli left join produk on produk.ProdID = beli.ProdID left join pengguna on pengguna.PUsername = beli.PUsername order by beli.BelID desc");
			while($row = $this->sql->assoc($result)){
				array_push($rod["fetchAllPayment"], array("ProdID"=>$row["ProdID"],"ProdPict"=>$row["ProdPict"],"Quantity"=>$row["Quantity"],"TotalPrice"=>$row["TotalPrice"], "PAddress"=>$row["PAddress"], "PUsername"=>$row["PUsername"],"BelDate"=>$row["BelDate"], "Status"=>$row["Status"]));
			}	
			$this->print($rod);
		}






		//SOIJFOSIDJFIOSOIRUQIEURIO AOOJEOIJSOIJF :D
		public function AA($data){
			date_default_timezone_set("Asia/Jakarta");
			$date = date("Y-m-d");
			$result = $this->sql->query("insert into produk (`ProdName`,`ProdDesc`,`ProdWeight`,`ProdPrice`,`ProdQuantity`,`ProdDate`,`ProdLove`,`ProdComm`,`ProdPict`,`PUsername`) values ('".$data["ProdName"]."','".$data["ProdDesc"]."','".$data["ProdWeight"]."','".$data["ProdPrice"]."','".$data["ProdQuantity"]."','".$date."','0','0','".$data["ProdPict"]."','".$data["PUsername"]."')");
			if($result){
				$this->BB($data);
			}else{
				$this->print(array("newProduct"=>array("status"=>false,"msg"=>"Tidak dapat menambahkan produk baru.")));
			}
		}
		function BB($data){
			//pake fopen,fwrite ae di nasiwebhost ga bisa pake file_put_contents()
			$path = __DIR__."/img/produk/";
			if(file_exists($path.$data["ProdPict"])){
				unlink($path.$data["ProdPict"]);
			}
			$file = fopen($path.$data["ProdPict"], "x");
			if(fwrite($file, base64_decode($data["imageData"]))){
				fclose($file);
				$this->print(array("newProduct"=>array("status"=>true,"msg"=>"Produk berhasil ditambahkan.")));
			}else{
				$this->print(array("newProduct"=>array("status"=>true,"msg"=>"Gambar produk gagal di unggah.")));
			}
		}

		public function print($string){
			echo json_encode($string);
		}
	}
?>