<?php
class File
{
	public $valid_extensions = array(".doc",".txt",".pdf",".rtf");
	public $type = FALSE;
	public $name = FALSE;
	public $size = FALSE;
	public $tmp = FALSE;
	public $ext = FALSE;
	public $file = FALSE;

	public function __construct($config=""){

	}
	
	public function prepare_file($file="upload"){
		if(is_uploaded_file($_FILES[$file]['tmp_name'])){
			$this->type = $_FILES[$file]['type'];
			$this->name = $_FILES[$file]['name'];
			$this->size = $_FILES[$file]['size'];
			$this->tmp = $_FILES[$file]['tmp_name'];
			$this->ext = strtolower(strrchr($this->name,'.'));
			if(in_array($this->ext, $this->valid_extensions)){
				$this->get_contents();
				return TRUE;
			}else{
				$this->tmp = NULL;
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	public function get_contents(){
		if(is_uploaded_file($this->tmp)){
			$this->file = file_get_contents($this->tmp);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	public function __destruct(){
	
	}
}
?>