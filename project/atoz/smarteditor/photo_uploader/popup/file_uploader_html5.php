<?php
 	$sFileInfo = '';
	$headers = array();

	foreach($_SERVER as $k => $v) {
		if(substr($k, 0, 9) == "HTTP_FILE") {
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		}
	}

	$file = new stdClass;

	$m = substr(microtime(),2,4);
	$new_file_name = date("YmdHis").$m.eregi_replace("(.+)(\.[gif|jpg|png|jpeg|bmp])","\\2",rawurldecode($headers['file_name']));
	#$file->name = rawurldecode($headers['file_name']);
	$file->name = $new_file_name;
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

	$uploadDir = '../upload/';
	if(!is_dir($uploadDir)){
		mkdir($uploadDir, 0777);
	}

	$newPath = $uploadDir.iconv("utf-8", "cp949", $file->name);

	if(file_put_contents($newPath, $file->content)) {
		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=/smarteditor/photo_uploader/upload/".$file->name;
	}

	echo $sFileInfo;
 ?>