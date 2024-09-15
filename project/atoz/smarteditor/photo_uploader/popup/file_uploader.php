<?php
echo $_REQUEST["htImageInfo"];

// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];

	$uploadDir = '../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}

	$m = substr(microtime(),2,4);
	$new_file_name = date("YmdHis").$m.eregi_replace("(.+)(\.[gif|jpg|png|jpeg|bmp])","\\2",$_FILES['Filedata']['name']);

	#$newPath = $uploadDir.urlencode($_FILES['Filedata']['name']);
	$newPath = $uploadDir.$new_file_name;

	move_uploaded_file($tmp_name, $newPath);

	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	#$url .= "&sFileURL=/smarteditor/photo_uploader/upload/".urlencode(urlencode($name));
	$url .= "&sFileURL=/smarteditor/photo_uploader/upload/$new_file_name";
}else{
	// FAILED
	$url .= '&errstr=error';
}

header('Location: '. $url);
?>