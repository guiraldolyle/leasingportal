<?php 
Class Validators{
	public $file;

	function __construct($file){
		$this->file = $file;
	}

	private function push_error($validator, $message){
    	$this->file->errors[$validator] = $message;
    	$this->file->uploadOk =false;
    }


	public function required(){
        if(empty($this->file->file)){
        	$this->push_error("required", $this->file->field_name." is required.");
        }

        return $this;
    }

    public function multiple(){
        if($this->file->upload_type != "multiple" && !is_null($this->file->upload_type)){
        	$this->push_error("multiple", "Field ".$this->file->field_name." is expected to be a multiple file upload.");
        }

        return $this;
    }

    public function max_file($num){
        
        if( count($this->file->file) > $num && $this->file->upload_type == "multiple"){
        	$this->push_error("max_file", " A maximum of $num files are allowed to upload for field ".$this->file->field_name);
        }

        return $this;
    }

    public function min_file($num){
        
        if( count($this->file->file) < $num && $this->file->upload_type == "multiple"){
        	$this->push_error("min_file", " A minimum of $num files are required to upload for field ".$this->file->field_name);
        }

        return $this;
    }

    public function image(){
        if($this->file->upload_type == "single"){
            $check = getimagesize($this->file->file["tmp_name"]);
            if($check === false) {
            	$this->push_error('image', "File ".basename($this->file->file["name"])." is not an image."); 
            }
        } elseif($this->file->upload_type == "multiple"){

            foreach ($this->file->file as $key => $file) {

                $check = getimagesize($file["tmp_name"]);

                if($check === false) {
                	$this->push_error('image-'.basename($file["name"]), "File ".basename($file["name"])." is not an image."); 
                }
            }
        }
        
        return $this;
    }

    public function exist($dir=""){
    	$dir = rtrim($dir, "/")."/";

        if($this->file->upload_type == "single"){
            if (file_exists($dir.basename($this->file->file["name"]))) {
            	$this->push_error('exist', "File ".basename($this->file->file["name"])."  already exists.");
            } 
        } elseif($this->file->upload_type == "multiple"){

            foreach ($this->file->file as $key => $file) {
                if (file_exists($dir.basename($file["name"]))) {
                	$this->push_error('exist-'.basename($file["name"]), "File ".basename($file["name"])."  already exists.");
                } 
            }
        }

        return $this;
    }

    public function min_size($size_mb){
      	$size = $size_mb * 1000000;

        if($this->file->upload_type == "single"){

            if ($this->file->file["size"] < $size) {
            	$this->push_error('min_size', "File ".basename($this->file->file["name"])."  size too small. Minimum size allowed is $size_mb MB.");
            } 
        }elseif($this->file->upload_type == "multiple"){
            foreach ($this->file->file as $key => $file) {
                
                if ($file["size"] < $size) {
                   $this->push_error('min_size-'.basename($file["name"]), 
                   	"File ".basename($file["name"])."  size too small. Minimum size allowed is $size_mb MB.");  
                } 

            }
        }

        return $this;
    }

    public function max_size($size_mb){
        $size = $size_mb * 1000000;

        if($this->file->upload_type == "single"){
            
            if ($this->file->file["size"] > $size) {
            	$this->push_error('max_size', "File ".basename($this->file->file["name"])."  size too large. Maximum size allowed is $size_mb MB.");
            }

        } elseif($this->file->upload_type == "multiple"){
            foreach ($this->file->file as $key => $file) {

                if ($file["size"] > $size) {
                    $this->push_error('max_size-'.basename($file["name"]), 
                   	"File ".basename($file["name"])."  size too large. Maximum size allowed is $size_mb MB.");             
                } 

            }
        }

        return $this;
    }

    //CHECK FILE FOR VALID FILE (FORMATS OR FILE EXTENSION IS NEEDED AS PARAMETER AND SHOULD BE IN ARRAY TYPE)
    public function valid_format(array $formats){

        if($this->file->upload_type == "single"){
            
            if (!in_array(pathinfo($this->file->file['name'],PATHINFO_EXTENSION), $formats)) {
            	$this->push_error('valid_format', "File ".basename($this->file->file["name"])." has an invalid file format.");
            } 
        } elseif($this->file->upload_type == "multiple"){
            foreach ($this->file->file as $key => $file) {

                if (!in_array(pathinfo($file['name'],PATHINFO_EXTENSION), $formats)) {
                	$this->push_error('valid_format-'.basename($file["name"]), 
                   	"File ".basename($file["name"])."  has an invalid file format.");
                } 

            }
        }
        
        return $this;  
    }

    public function move($dir="", $retain_name = true){

    	if ($this->file->uploadOk == false) {
        	$this->push_error('move', 
               	"Error found while uploading the file from field ".$this->file->field_name.", please try again!");

        	return false;
    	}

    	if($this->file->upload_type == "single"){
    		
			$dir = $retain_name ? rtrim($dir, "/")."/".$this->file->file["name"] : $dir; 

            if (!move_uploaded_file($this->file->file["tmp_name"], $dir)) { 
                $this->push_error('move', 
               	"There was an error uploading your file\\nFILE NAME : ".basename($this->file->file["name"]));
               	return false;
            }

            return true;
    	} elseif($this->file->upload_type == "multiple"){

    		$uploadSuccess = true;

    		foreach ($this->file->file as $key => $file) {

    			$extension = ".".pathinfo( $retain_name ? $file['name'] : $dir ,PATHINFO_EXTENSION);

    			$filedir = $retain_name ? 
    				rtrim($dir, "/")."/".$file["name"] : 
    				rtrim($dir, $extension).$key.$extension;

	            if (!move_uploaded_file($file["tmp_name"], $filedir)) {

	                $this->push_error('move-'.basename($file["name"]), 
                   	"There was an error uploading your file\\nFILE NAME : ".basename($file["name"]));

                   	$uploadSuccess = false;
	            }

	        }

	        return $uploadSuccess;
    	} 
    }


    public function get(){
       return $this->file->file;
    }
  }