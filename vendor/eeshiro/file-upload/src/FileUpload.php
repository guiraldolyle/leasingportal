<?php 
require_once "Validators.php";

class FileUpload
{	
	private $instances = [];
	private $files = [];
	private $file_container = [];

	

	function __construct($files = null){

		if($files) 
			$this->files = $files;
		else
			$this->files = $_FILES;
	}

	public function validate($index, $field = null){
		if(!isset($this->instances[$index]) || !$this->instances[$index] instanceof Validators){
			
			if(isset($this->file_container[$index])){
				$file = $this->file_container[$index];
			}else{
				$this->file_container[$index]  = $file = $this->checkAndPrepare($index, $field);
			}

			$this->instances[$index] = new Validators($file);
		}


		return $this->instances[$index];
		
	}

	private function checkAndPrepare($index, $field_name = null){
		$field_name = $field_name ? $field_name : ucwords($index);
		$uploadOk = true; 
		$errors = [];

        $data = $this->arrangeFiles($index);

        $file = $data['file'];
        $upload_type = $data['upload_type'];

        return (object) compact('file', 'upload_type', 'field_name', 'uploadOk', 'errors');
    }


    private function arrangeFiles($index){

    	if(isset($this->files[$index]) && !empty($this->files[$index])){

            if(empty($this->files[$index]["name"]) ||  empty($this->files[$index]["name"][0])){
                $file = [];
                $upload_type = null;
            }
            elseif(is_array($this->files[$index]["name"])){
            	$file = $this->multi_prepare($this->files[$index]);
                $upload_type = "multiple";
            } else {
                $file = $this->files[$index];
                $upload_type = "single";
            } 
        } else{ 
            $file = [];
            $upload_type = null;
        }

        return compact('file', 'upload_type');

    }


    //USE THIS TO PREPARE FOR MULTIPLE FILE UPLOAD
    private function multi_prepare($multi_file){
        $multi=array();
        foreach($multi_file['name'] as $key =>$f){
            $file['name']       = $multi_file['name'][$key];
            $file['type']       = $multi_file['type'][$key];
            $file['tmp_name']   = $multi_file['tmp_name'][$key];
            $file['error']      = $multi_file['error'][$key];
            $file['size']       = $multi_file['size'][$key];
            $multi[] = $file;
        }

        return $multi;
    }

     //TO GET THE ERRORS STORED ON $error VARIABLE USE get_error() FUNCTION
    public function error_count($index = null){

    	$count = 0;

    	if($index){
    		if(isset($this->instances[$index])){
    			$count = count($this->instances[$index]->file->errors);
    		}
    	}else{
    		foreach ($this->instances as $key => $instance) {
	    		$count += count($instance->file->errors);
	    	}
    	}

        return $count;
    }

    public function has_error($index = null){
    	return $this->error_count($index) > 0;
    }

    public function get_errors($glue = null, $index=null){

    	$errors = [];
    	if($index){


    		if(isset($this->instances[$index])){
    			$errors = array_values($this->instances[$index]->file->errors);
    		}
    	}else{
    		foreach ($this->instances as $key => $instance) {
    			$err = array_values($instance->file->errors);
	    		$errors = array_merge($errors, $err);
	    	}
    	}

    	$errors = $glue ? implode($glue, $errors) : $errors;

        return $errors;
    }

    public function get($index){
    	if(isset($this->instances[$index])){
			return $this->instances[$index]->get();
		}

		return $this->arrangeFiles($index)['file'];
    }


    public function move_uploaded_file(array $file, $dir){
    	if (!move_uploaded_file($file["tmp_name"], $dir)) { 
           	return false;
        }

        return true;
	}

}