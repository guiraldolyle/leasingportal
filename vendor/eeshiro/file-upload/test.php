<?php 
require "src/FileUpload.php";

if($_SERVER["REQUEST_METHOD"] == 'POST'){
	$upload = new FileUpload;
	$upload->validate('single', 'Single')->required()->min_size(5)->get();
	$file = $upload->validate('multiple', 'Multiple')->required()->min_size(6)->get();
	var_dump($upload->move_uploaded_file($file[0], 'uploads/file'.time().'.txt'));
}
 ?>

<form action="<?= $_SERVER["PHP_SELF"]?>" method="post" enctype="multipart/form-data">
  Select image to upload:
  <input type="file" name="single">
  <input type="file" name="multiple[]" multiple="">
  <input type="submit" value="Upload Image" name="submit">
</form>
