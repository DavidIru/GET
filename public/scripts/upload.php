<?php

// This is a simplified example, which doesn't cover security of uploaded images.
// This example just demonstrate the logic behind the process.

$dir = '/img/promociones/';

$contentType = $_POST['contentType'];
$data = base64_decode($_POST['data']);

$filename = md5(date('YmdHis')).'.png';
$file = $dir.$filename;

file_put_contents($file, $data);

echo json_encode(array('filelink' => $file));
?>