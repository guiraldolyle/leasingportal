<?php 

if (!defined('NAM_DEBUG_MODE')) {
    define('NAM_DEBUG_MODE', TRUE);
}

class Dumper
{
	
	public static function dump($data = NULL, $is_html_encode = true){
		self::dn($data, $is_html_encode);
	}

	private static function d($data = NULL, $is_output = true, $is_html_encode = true, $isObject = false){
		$showAll = true;

	    if(NAM_DEBUG_MODE === FALSE)return '';

	    $data_type =  $data instanceof \Exception ? 'Exception' : gettype($data);

	    $str = $is_output ? "<pre>" : '';

	    if(!$isObject){
	    	$str.= is_null($data) ? "": "<i>$data_type";
		    $str.= $data_type == 'array' ? ":".count($data) : "";
		    $str.="</i>";
		    $str.= is_null($data) ? "": " ";
	    }

	    if(is_null($data)){
	        $str .= "<font color='red'><i>$data_type</i></font>";
	    }elseif($data === ""){
	        $str .= " <font color='red'><i>\"\"</i></font>";
	    }elseif($data instanceof \Exception){
	        $data_exception = [
	        	'code' 			=>$data->getCode(),
	        	'message' 		=>$data->getMessage(),
	        	'file'			=>$data->getFile(),
	        	'line'			=>$data->getLine(),
	        	'previous'		=>$data->getPrevious(),
	        	'details'		=> preg_replace("/(\r|\n)/", " ", $data->__toString())
	        ];

	        $str.= ': '.self::d($data_exception, false, true, true);
	       
	    }elseif(is_array($data)){
	        if(count($data) === 0){
	            $str .= "[]";
	        }else{
	        	$id = uniqid();
	           	$str.= (isAssoc($data) ? '{' : '[');

	           	$str .= '<button style="color:grey;border:0"'."onclick='toggleTd(\"$id\")' id=\"arrow-$id\">▶</button>";
	            $str .= "<div id=\"$id\" style=\"padding-left: 30px;" . ($is_output ? '">' : ($showAll ? '">' :'display: none;">'));

	            foreach ($data as $key => $value) {
	                $str .= "<div>";
	                $str .= "<span style=\"vertical-align: top;\" align=\"left\">";
	                $str .= '<font color="'.(!is_int($key) ? '#633333' : 'red').'">';
	                $str .= is_int($key) ? "$key => ":"'$key': ";
	                $str .= "</font>";
	                $str .= "</span>";
	                $str .= "<span  style=\"padding-left: 5px;\">".self::d($value, false)."</span>";
	                $str .= "</div>";
	            }

	            $str .= "</div>";


	            $str.= (isAssoc($data) ? '}' : ']');
	        }
	    }elseif(is_resource($data)){
	        $data_array = mysqli_fetch_all($data);
	        $str .= self::d($data_array, false);
	    }elseif(is_object($data)){

	        $reflect                    = new \ReflectionClass($data);
	        $className                  = $reflect->getName();

	        $arr["FullClassPathName"]       = $className;
	        $arr["Namespace"]               = $reflect->getNamespaceName();
	        $arr["ShortClassName"]          = $reflect->getShortName();

	        if(in_array($arr["ShortClassName"], array('mysql_result', 'mysqli_result'))){
	            unset($arr["FullClassPathName"]);

	            $arr["fetch_all"]           = $data->fetch_all();

	        }else{
	            $arr["Attributes"]          = get_object_vars($data);
	            $arr["Methods"]             = get_class_methods($className);
	        }

	        if(empty($arr["Namespace"])){
	            unset($arr["Namespace"]);
	        }
	        if(empty($arr["Methods"])){
	            unset($arr["Methods"]);
	        }

	        $str .= ': '.self::d($arr, false, true, true);
	    }elseif(is_numeric($data) && (gettype($data) !== "string")){
	        $str .= "<font color='red'><i>" . $data . "</i></font>";
	    }elseif(is_bool($data) && ($data === true || $data === false)){
	        $str .= "<font color='red'><i>" . (($data === true) ? "True" : "False") . "</i></font>";
	    }else{
	        
	        if($is_html_encode){
	            $data = htmlspecialchars($data);
	        }else{
	        	$data = $data;
	        }

	        $str .= '<font color="brown;">"'.preg_replace("/(\r|\n)/", "<br>" . PHP_EOL, $data).'"</font>';
	    }

	    $str .= $is_output ? "</pre>" : '';
      	$str .= $is_output ? "<script> 
            function toggleTd(id) {
              var x = document.getElementById(id);
              var y = document.getElementById('arrow-'+id);
              if (x.style.display === \"none\") {
                x.style.display = \"\";
                y.innerHTML = '▼';
              } else {
                x.style.display = \"none\";
                y.innerHTML = '▶';
              }
            }
        </script>" : '';

	    if($is_output){
	        echo $str;
	    }
	    return $str;
	}

	private static function dn($data = NULL, $is_html_encode = true){
	    self::d($data, true, $is_html_encode);
	    //echo "<br>" . PHP_EOL;
	}

	public static  function dd($data = NULL, $is_html_encode = true){
	    self::dn($data, $is_html_encode);
	    exit;
	}

	public static function djson($json = NULL, $isExited = false){
	    if(is_string($json)){
	        $json = json_decode($json);
	    }

	    self::dn($json);

	    if($isExited){
	        exit;
	    }
	}

	public static function ddjson($json = NULL){
	    self::djson($json, true);
	}

}

if(!function_exists('dump')){
	function dump($data=NULL, $is_html_encode = true){
		Dumper::dump($data, $is_html_encode);
	}
}

if(!function_exists('dd')){
	function dd($data = NULL, $is_html_encode = true){
		Dumper::dd($data, $is_html_encode);
	}
}
if(!function_exists('djson')){
	function djson($data = NULL, $is_exited = false){
		Dumper::djson($data, $is_exited);
	}
}
if(!function_exists('ddjson')){
	function ddjson($data = NULL){
		Dumper::ddjson($data, true);
	}
}

if(!function_exists('isAssoc')){
	function isAssoc(array $arr)
	{
	    if (array() === $arr) return false;
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}
}