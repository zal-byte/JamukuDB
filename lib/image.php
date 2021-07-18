
<?php

	// $img=Image::getInstance();

	// if($_SERVER['REQUEST_METHOD']=='POST'){
	// 	if(isset($_FILES['file'])){
	// 		if($_FILES['file']['type'] == 'image/jpeg'){
	// 			$img->ImageQuality($_FILES['file'], Image::JPG_DEF);
	// 		}else if($_FILES['file']['type'] == 'image/png'){
	// 			$img->ImageQuality($_FILES['file'], Image::PNG_DEF);
	// 		}
	// 	}
	// }
	class Image{
		private static $instance = null;
		public static function getInstance(){
			if(self::$instance == null){
				self::$instance = new Image();
			}
			return self::$instance;
		}
		public const JPG_LOW = 25;
		public const JPG_MIDDLE = 60;
		public const JPG_DEF = 75;
		public const JPG_HIGH = 100;

		public const PNG_LOW = 3;
		public const PNG_MIDDLE = 5;
		public const PNG_DEF = 6;
		public const PNG_HIGH = 9;

		public function ImageQuality($tmp = null, $where, $additional = null){
			if($tmp['type'] == 'image/jpeg'){
				$this->jpg(['1'=>$where,'file'=>$tmp]);
			}else if($tmp['type'] == 'image/png'){
				$this->png(['1'=>$where,'file'=>$tmp]);
			}

			if($additional != null){
				$this->byte(['1'=>$where, 'file'=>$additional]);
			}
		}
		public function dir($path = null){
			if($path != null ){
				$open = fopen($path, 'r');
				$p = explode('=', fgets($open));
				if($p[0]=='path'){
					if($p[1] != null || $p[1] != ''){
						return $p[1];
					}else{
						die("Error, path is null or undefined");
					}
				}else{
					die("Error, couldn't find the 'path' parameter");
				}
				fclose($open);
			}else{
				die("path : null");
			}
		}

		public function funcDir(){
			return $this->dir(__DIR__.'/'.'upload.path');
		}
		public function isFileExists($path){
			if(file_exists($path)){
				unlink($path);
			}
		}
		public function byte($param){
			$filename = time() . "_" .uniqid() . "_" . $param['file']['filename'];
			if(imagejpeg(imagecreatefromstring($param['file']['imageData']), __DIR__ . "/" . $this->funcDir() . "/" . $filename, $param['1'])){
				$this->print(array('byte'=>['status'=>true,'filename'=>$filename]));
			}else{
				$this->print(array('byte'=>['status'=>false,'msg'=>'Upload failed']));
			}
		}
		public function jpg($param){
			$filename = time() . "_" . uniqid() . "_" . $param['file']['name'];
			if(imagejpeg(imagecreatefromjpeg($param['file']['tmp_name']), __DIR__ . "/" . $this->funcDir() . "/" . $filename, $param['1'])){
				$this->print(array('jpg'=>['status'=>true,'filename'=>$filename]));
			}
		}
		public function png($param){
			$filename = time() . "_". uniqid() . "_" . $param['file']['name'];
			if(imagepng( imagecreatefrompng($param['file']['tmp_name']), __DIR__ . "/" . $this->funcDir() . "/" . $filename, $param['1'] )){
				$this->print(array('png'=>['status'=>true,'filename'=>$filename]));
			}
		}
		private function print($string){
			echo json_encode($string);
		}
	}

?>