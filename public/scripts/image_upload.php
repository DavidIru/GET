<?php

// files storage folder
$dir = '/home/vicky/public_html/redactor/img/';

$_FILES['file']['type'] = strtolower($_FILES['file']['type']);
$extension = explode('/', $_FILES['file']['type']);
$extension = $extension[1];
if ($extension == 'png' || $extension == 'jpg' || $extension == 'gif'
|| $extension == 'jpeg' || $extension == 'pjpeg') {
    $filename = md5(date('YmdHis')).".".$extension;
    $file = $dir.$filename;
	
	//Copiando
	//move_uploaded_file($_FILES['file']['tmp_name'], $file);
	copy($_FILES['file']['tmp_name'], $file);
	//Mostrando archivo
	$array = array(
		'filelink' => $file
	);

	echo stripslashes(json_encode($array));
}

?>