<?php 
class DecimalToWord
{	

	private static $ones = [0 => "",
			        	    1 => "One ",     
				            2 => "Two ", 
				            3 => "Three ", 
				            4 => "Four ", 
				            5 => "Five ", 
				            6 => "Six ", 
				            7 => "Seven ", 
				            8 => "Eight ", 
				            9 => "Nine ", 
				            10 => "Ten ", 
				            11 => "Eleven ", 
				            12 => "Twelve ", 
				            13 => "Thirteen ", 
				            14 => "Fourteen ", 
				            15 => "Fifteen ", 
				            16 => "Sixteen ", 
				            17 => "Seventeen ", 
				            18 => "Eighteen ", 
				            19 => "Nineteen "];

	private static $tens = ["2"	=>	" Twenty-",
							"3"	=>	" Thirty-",
							"4"	=>	" Forty-",
							"5"	=>	" Fifty-",
							"6"	=>	" Sixty-",
							"7"	=>	" Seventy-",
							"8"	=>	" Eighty-",
							"9"	=>	" Ninety-"];

	private static $hundreds = [0 => "",
								1 => " Hundred", 
	            	     		2 => " Thousand, ", 
	            	     		3 => " Million, ", 
	            	     		4 => " Billion, ", 
	            	     		5 => " Trillion, ", 
	            	     		6 => " Quadrillion, "];

	public static $formatted = 0;

	public static $currency = "";
	public static $curr_dec = "";
	
	public static function convert($decimal, $currency="", $curr_dec = null){
		self::$currency = $currency;
		self::$curr_dec = $curr_dec;

		if($decimal == 0){
			return "Zero ".$currency;
		}

		if(!is_numeric($decimal)) return "(Invalid value)";

		$negative = ((double)$decimal < 0 ? true : false);

		//format to number
		$formatted = number_format($decimal, 2);
		self::$formatted = $formatted;

		$exp_num = explode(".", ltrim($formatted, "-"));

		$int = $exp_num[0];
		$dec = $exp_num[1];

		$converted_dec  = self::generate($dec, 0);

		$dec_separator = $curr_dec ? ' and ' : ' point ';

		$converted_dec = ((int)$int != 0 && (int)$dec !=0 ? $dec_separator.$converted_dec : $converted_dec );


		$exp_int = explode(",", strrev($int));

		if(count($exp_int) > 6) return "(Unable to convert value)";

		foreach ($exp_int as $key => $value) {

			$value = strrev($value);
			$converted [] = self::generate($value, $key+1);
		}

		$stmt = ($negative ? "Negative ": "").rtrim(implode("", array_reverse($converted)), ", ");

		$stmt = ((int)$int != 0 ? $stmt." ".$currency : $stmt ).$converted_dec;

		return $stmt;
	}

	private static function generate($value, $pos){
		$split_2 		= str_split(strrev($value), 2);
		$split_2[0] 	= (int)strrev($split_2[0]);
		$split_1 		= str_split(strrev($value), 1);

		$stmt= "";

		if((int)$split_2[0] < 20){
			if(count($split_2)==2){
			 	$stmt.= self:: $ones[$split_2[1]].($split_2[1] != 0 ? " Hundred ":"");
			}
			$stmt.= self:: $ones[$split_2[0]];
		} else{

			switch (count($split_1)) {
				case 3:
					$stmt.= self:: $ones[$split_1[2]].($split_1[2] != 0 ? " Hundred ":"");

				case 2: 
					$stmt.= self:: $tens[$split_1[1]];
				
				default:
					$stmt.= self:: $ones[$split_1[0]];
					break;
			}
		}

		$stmt = rtrim($stmt,"-");

		return $stmt.= ($pos > 1 && $value !=0 ? self::$hundreds[$pos] : 
			($pos == 0 && (int)$value != 0 ? " ".self::$curr_dec: "")
		);
	}
}


/*=================================================================================

$number = "";
if(isset($_REQUEST["number"])){
	$number = $_REQUEST["number"];
	echo DecToWord::convert($number, "Pesos")."<br>";
}

?>

<form method="get">
	<input type="number" name="number" step="any" value="<?=$number?>" id="num">
</form>

<script type="text/javascript">
	document.getElementById("num").focus();
</script>

=================================================================================*/

 //echo DecToWord::convert(12343423.99, "Pesos")."<br>";