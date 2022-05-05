<?php
/*
    set()   = USE TO SET A FILE TO BE UPLOADED
            PARAMETER:  FILE & DIRECTORY
            EX. set($_FILE['file'], "Name");
    
    check_image = USE TO CHECK FILE AS A VALID IMAGE
            PARAMETER: NONE
            EX: checkimage();

    exist() = USE TO CHECK THE FILE IF IT ALREADY EXISTS
            PARAMETER: NONE
            EX: exist();

    valid_size() = USE TO VALIDATE A FILE SIZE
            PARAMETER: FILE SIZE IN KILOBYTES (KB) 1mb == 1024 kb
            EX: valid_size(1024); (1mb)

    valid_format() = USE TO VALIDATE THE FORMAT OF THE FILE
            PARAMETER: FILE FORMATS IN ARRAY;
            EX: valid_format(array("jpg", "img", "png", "gif"));

    
    save()  = USE THE UPLOAD OR MOVE THE FILE AFTER IT HAS BEEN VALIDATED
            PARAMETER: save($dir="", $index = null)
            EX: save($dir, $tile_index)



//////////////////////////////////////////////////////////////////////////////////
    
    multi_prepare() = RETURN THE ARRAY OF THE MULTIPLE FILES FROM THE HTML FORM
            PARAMETER: FILES
            EXAMPLE: multi_prepare($_FILES['file'], '../dir/');

    fileErrorCount = TOTAL FILES WITH ERROR
            TYPE: VARIABLE

    totalFile = TOTAL FILES UPLOADED
            TYPE: VARIABLE

    TOTAL FILES UPLOADED SUCCESSFULLY = totalFile -fileErrorCount;

*/

class FileUpload
{
    
    public $file=[];
    private $index;
    private $name;
    private $upload_type;
    public $uploadOk = 1;
    public $errors = [];
    

    
    public function get_file($index){
        if(isset($_FILES[$index]) && !empty($_FILES[$index])){

            if(empty($_FILES[$index]["name"]) ||  empty($_FILES[$index]["name"][0])){
                $file = [];
                $this->upload_type = null;
            }
            elseif(is_array($_FILES[$index]["name"])){

                $this->upload_type = "multiple";
                $file = $this->multi_prepare($_FILES[$index]);

            } else {
                $file = $_FILES[$index];
                $this->upload_type = "single";
            } 

            
        } else{ 
            $file = [];
            $this->upload_type = null;
        }

        return $file;

    }


    public function set($index, $name=""){
        $this->index = $index;
        $this->file[$this->index] = $this->get_file($index);
        $this->name = $name == "" ? $index : $name;

        return $this;
    }

    public function required(){
        if(is_null($this->upload_type)){
            $this->errors[$this->index]["required"] = " $this->name is required.";
        }

        return $this;
    }

    public function multiple(){
        if($this->upload_type != "multiple" && !is_null($this->upload_type)){
            $this->errors[$this->index]["multiple"] = " Input field $this->name is expected to be a multiple file upload.";
        }

        return $this;
    }

    public function max_file($num){
        
        if(count($this->file[$this->index]) > $num && $this->upload_type == "multiple"){
            $this->errors[$this->index]["max_file"] = " A maximum of $num files are allowed to upload.";
        }
        return $this;
    }
    public function min_file($num){
        if(count($this->file[$this->index]) < $num || is_null($this->upload_type)){
            $this->errors[$this->index]["max_file"] = " A minimum of $num files are allowed to upload.";
        }
        return $this;
    }

    public function rename($name){
        $this->fileName = $name . $this->fileType;
        $this->target_file = $this->dir . $this->fileName;
    }

    //CHECK IF FILE IS AND IMAGE
    public function image(){
        if($this->upload_type == "single"){
            $check = getimagesize($this->file[$this->index]["tmp_name"]);
            if($check === false) {
                $this->errors[$this->index][basename($this->file[$this->index]["name"])]["image"]="File ".basename($this->file[$this->index]["name"])." is not an image.";
                $this->file[$this->index]["uploadOk"] = 0;
                
            }
        } elseif($this->upload_type == "multiple"){

            foreach ($this->file[$this->index] as $key => $files) {

                $check = getimagesize($files["tmp_name"]);

                if($check === false) {
                    $this->errors[$this->index][basename($files["name"])]["image"]="File ".basename($files["name"])." is not an image.";
                    $this->file[$this->index][$key]["uploadOk"] = 0;                  
                }
            }
        }
        
        return $this;
    }

    //CHECK IF FILE IS ALREADY EXISTS
    public function exist($dir=""){

        if($this->upload_type == "single"){
            if (file_exists($dir.basename($this->file[$this->index]["name"]))) {

                $this->errors[$this->index][basename($this->file[$this->index]["name"])]["exist"]= "File ". basename($this->file[$this->index]["name"]) ." already exists.";
                $this->file[$this->index]["uploadOk"] = 0;
            } 

        } elseif($this->upload_type == "multiple"){

            foreach ($this->file[$this->index] as $key => $files) {

                if (file_exists($dir.basename($files["name"]))) {

                    $this->errors[$this->index][basename($files["name"])]["exist"]= "File ". basename($files["name"]) ." already exists.";
                    $this->file[$this->index][$key]["uploadOk"] = 0; 
                } 
            }
        }

        return $this;
    }

    public function min_size($size_mb){
      $size = $size_mb * 1000000;

        if($this->upload_type == "single"){

            if ($this->file[$this->index]["size"] < $size) {

                $this->errors[$this->index][basename($this->file[$this->index]["name"])]["min_size"]= "File ". basename($this->file[$this->index]["name"]) ." size too small. Minimum size allowed is $size_mb MB";
                $this->file[$this->index]["uploadOk"] = 0;

            } 
        } elseif($this->upload_type == "multiple"){
            foreach ($this->file[$this->index] as $key => $files) {
                
                if ($files["size"] < $size) {
                   
                    $this->errors[$this->index][basename($files["name"])]["min_size"] = "File ". basename($files["name"]) ." size too small. Minimum size allowed is $size_mb MB";
                    $this->file[$this->index][$key]["uploadOk"] = 0; 
                } 

            }
        }

        return $this;
    }

    public function max_size($size_mb){
        $size = $size_mb * 1000000;

        if($this->upload_type == "single"){
            
            if ($this->file[$this->index]["size"] > $size) {

                $this->errors[$this->index][basename($this->file[$this->index]["name"])]["max_size"]= "File ". basename($this->file[$this->index]["name"]) ." size too large. Maximum size allowed is $size_mb MB";
                $this->file[$this->index]["uploadOk"] = 0;

            } 
        } elseif($this->upload_type == "multiple"){
            foreach ($this->file[$this->index] as $key => $files) {

                if ($files["size"] > $size) {

                    $this->errors[$this->index][basename($files["name"])]["max_size"] = "File ". basename($files["name"]) ." size too large. Maximum size allowed is $size_mb MB";
                    $this->file[$this->index][$key]["uploadOk"] = 0; 
                } 

            }
        }

        return $this;
    }

    //CHECK FILE FOR VALID FILE (FORMATS OR FILE EXTENSION IS NEEDED AS PARAMETER AND SHOULD BE IN ARRAY TYPE)
    public function valid_format($formats){

        if($this->upload_type == "single"){
            
            if (!in_array(pathinfo($this->file[$this->index]['name'],PATHINFO_EXTENSION), $formats)) {

                $this->errors[$this->index][basename($this->file[$this->index]["name"])]["valid_format"]= "File ". basename($this->file[$this->index]["name"]) ." has an invalid file format.";
                $this->file[$this->index]["uploadOk"] = 0;

            } 
        } elseif($this->upload_type == "multiple"){
            foreach ($this->file[$this->index] as $key => $files) {

                if (!in_array(pathinfo($files['name'],PATHINFO_EXTENSION), $formats)) {

                    $this->errors[$this->index][basename($files["name"])]["valid_format"] = "File ". basename($files["name"]) ." has an invalid file format";
                    $this->file[$this->index][$key]["uploadOk"] = 0; 
                } 

            }
        }
        
        return $this;  
    }

    //UPLOAD AND MOVE THE FILE TO THE DIRECTORY
    public function save($dir="", $index = null){
        // Check if $uploadOk is not set to 0 by an error
        $index = is_null($index) ? $this->index : $index;
        if ($this->uploadOk == 1) {   
            if (move_uploaded_file($this->file[$index]["tmp_name"], $dir)) {
                return true;
            } else {
                $this->fileErrorCount++;
                $this->error[] = "There was an error uploading your file\\nFILE NAME : ".basename($this->file["name"])."\\n";
                return false;
            }
        } else {
            $this->fileErrorCount++;
            $this->error[] = "Error found while uploading the file, please try again.\\nFILE NAME : ".basename($this->file["name"])."\\n";
            return false;
        }
    }
    
    public function get($index=""){
        $index = empty($index) ? $this->index : $index;
        return isset($this->file[$index]) ? (object)$this->file[$index] : null;
    }

//////////////////////////////////////////////////////////////////////////////////////////////


    //USE THIS TO PREPARE FOR MULTIPLE FILE UPLOAD
    public function multi_prepare($multi_file){
        $multi=array();
        foreach($multi_file['name'] as $key =>$f){
            $file['name']       = $multi_file['name'][$key];
            $file['type']       = $multi_file['type'][$key];
            $file['tmp_name']   = $multi_file['tmp_name'][$key];
            $file['error']      = $multi_file['error'][$key];
            $file['size']       = $multi_file['size'][$key];
            $file['uploadOk']   = 1;
            $multi[] = $file;
        }

        return $multi;
    }

    //TO GET THE ERRORS STORED ON $error VARIABLE USE get_error() FUNCTION
    public function error_count(){
        return count($this->errors);
    }

    public function get_error($index=""){
        $stmt = "";
        if(empty($index)){
            foreach ($this->errors as $name => $errors) {
                foreach ($errors as $fileName => $error) {
                   if(is_array($error)){
                        $stmt.= implode("<br>", $error)."<br>";
                   } else{
                        $stmt.=$error."<br>";
                   }              
                }
            }
        } else{
            $errors = isset($this->errors[$index]) ? $this->errors[$index] : []; 
            foreach ($errors as $fileName => $error) {
               if(is_array($error)){
                    $stmt.= implode("<br>", $error)."<br>";
               } else{
                    $stmt.=$error."<br>";
               }              
            }
        }

        return $stmt;
    }

    public function has_error($index = ""){
        $result = false;

        if(empty($index)){
            count($this->errors) ? $result = true : $result = false;
        } else{
            isset($this->errors[$index]) ? $result = true : $result = false;
        }

        return $result;
    }
}
