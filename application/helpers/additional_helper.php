<?php 

if(!function_exists('getCurrentDate')){
	function getCurrentDate($format = 'Y-m-d'){
		return date($format);
	}
}