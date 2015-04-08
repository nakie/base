<?php

class Image
{

	public $max_thumb_width = 100;
	public $min_thumb_width = 10;
	public $max_photo_width = 800;
	public $min_photo_width = 5;
	public $valid_extensions = array(".gif",".jpg",".png",".jpeg");
	public $type = FALSE;
	public $name = FALSE;
	public $size = FALSE;
	public $tmp = FALSE;
	public $ext = FALSE;

	public function __construct($config=""){
	
	}
	
	public function prepare_file($file="image"){
		if(is_uploaded_file($_FILES[$file]['tmp_name'])){
			$this->type = $_FILES[$file]['type'];
			$this->name = $_FILES[$file]['name'];
			$this->size = $_FILES[$file]['size'];
			$this->tmp = $_FILES[$file]['tmp_name'];
			$this->ext = strtolower(strrchr($this->name,'.'));
			if(in_array($this->ext, $this->valid_extensions)){
				return TRUE;
			}else{
				$this->tmp = NULL;
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public function prepare_thumb(){
		$this->thumb = $this->resize_image($this->max_thumb_width, $this->min_thumb_width);
	}
	
	public function prepare_image(){
		$this->photo = $this->resize_image($this->max_photo_width, $this->min_photo_width);
	}
	
	public function resize_image($max_width, $min_width){
		if(is_uploaded_file($this->tmp)){
			if($this->type == "image/pjpeg" || $this->type == "image/jpeg"){
				$new_img = imagecreatefromjpeg($this->tmp);
				$istype = "jpeg";
			}elseif($this->type == "image/x-png" || $this->type == "image/png"){
				$new_img = imagecreatefrompng($this->tmp);
				$istype = "png";
			}elseif($this->type == "image/gif"){
				$new_img = imagecreatefromgif($this->tmp);
				$istype = "gif";
			}
			list($width, $height) = getimagesize($this->tmp);
			$imgratio = $width / $height;
			if ($imgratio > 1){
				if($width > $max_width){
					$newwidth = $max_width;
					$newheight = $max_width / $imgratio;
				}elseif($width < $min_width){
					$newwidth = $min_width;
					$newheight = $min_width / $imgratio;
				}else{
					$newwidth = $width;
					$newheight = $height;
				}
			}else{
				if($height > $max_width){
					$newheight = $max_width;
					$newwidth = $max_width * $imgratio;
				}elseif($height < $min_width){
					$newheight = $min_width;
					$newwidth = $min_width * $imgratio;
				}else{
					$newheight = $width;
					$newwidth = $height;
				}
			}
			$resized_img = imagecreatetruecolor($newwidth, $newheight);
			imagecopyresized($resized_img, $new_img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
			switch($istype){
				case "jpeg":
					$newloc = "/tmp/".time().".jpg";
					imagejpeg($resized_img, $newloc);
					break;
				case "png":
					$newloc = "/tmp/".time().".png";
					imagepng($resized_img, $newloc);
					break;
				case "gif":
					$newloc = "/tmp/".time().".gif";
					imagegif($resized_img, $newloc);
					break;
			}
			
			return file_get_contents($newloc);
		}else{
			return FALSE;
		}
	}
	
	public function __destruct(){
	
	}
}
?>